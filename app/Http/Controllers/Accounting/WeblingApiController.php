<?php

namespace App\Http\Controllers\Accounting;

use App\Http\Controllers\Controller;
use App\Models\Accounting\Transaction;
use App\Models\Accounting\Wallet;
use App\Support\Accounting\Webling\Entities\Entrygroup;
use App\Support\Accounting\Webling\Entities\Period;
use App\Support\Accounting\Webling\Exceptions\ConnectionException;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\View\View;

class WeblingApiController extends Controller
{
    public function index(Wallet $wallet): View
    {
        $this->authorize('book-accounting-transactions-externally');

        setlocale(LC_TIME, \App::getLocale());

        $periods = Period::all()
            ->where('state', 'open')
            ->mapWithKeys(fn ($period) => [
                $period->id => (object) [
                    'title' => $period->title,
                    'from' => $period->from,
                    'to' => $period->to,
                    'months' => self::getMonthsForPeriod($wallet, $period->from, $period->to),
                ],
            ]);

        return view('accounting.webling.index', [
            'wallet' => $wallet,
            'periods' => $periods,
        ]);
    }

    private static function getMonthsForPeriod(Wallet $wallet, $from, $to): Collection
    {
        /** @var Collection $monthsWithTransactions */
        $monthsWithTransactions = Transaction::query()
            ->forWallet($wallet)
            ->forDateRange($from, $to)
            ->where('booked', false)
            ->selectRaw('MONTH(date) as month')
            ->selectRaw('YEAR(date) as year')
            ->groupByRaw('MONTH(date)')
            ->groupByRaw('YEAR(date)')
            ->orderBy('year', 'asc')
            ->orderBy('month', 'asc')
            ->get();

        return $monthsWithTransactions->map(function ($e) use ($wallet) {
            $date = Carbon::createFromDate($e->year, $e->month, 1);

            return (object) [
                'transactions' => Transaction::query()
                    ->forWallet($wallet)
                    ->forDateRange($date, $date->clone()->endOfMonth())
                    ->where('booked', false)
                    ->count(),
                'date' => $date,
            ];
        });
    }

    public function prepare(Wallet $wallet, Request $request): View
    {
        $this->authorize('book-accounting-transactions-externally');

        $this->validateRequest($request);

        $period = Period::find($request->period);

        $transactions = Transaction::query()
            ->forWallet($wallet)
            ->forDateRange($request->from, $request->to)
            ->forDateRange($period->from, $period->to)
            ->where('booked', false)
            ->orderBy('date', 'asc')
            ->get();
        $hasTransactions = ! $transactions->isEmpty();
        if ($hasTransactions) {
            $accountGroups = $period->accountGroups();
        }

        return view('accounting.webling.prepare', [
            'wallet' => $wallet,
            'period' => $period,
            'from' => new Carbon($request->from),
            'to' => new Carbon($request->to),
            'transactions' => $transactions,
            'assetsSelect' => $hasTransactions ? $this->getAccountSelectArray($accountGroups, 'assets') : [],
            'incomeSelect' => $hasTransactions ? $this->getAccountSelectArray($accountGroups, 'income') : [],
            'expenseSelect' => $hasTransactions ? $this->getAccountSelectArray($accountGroups, 'expense') : [],
            'actions' => [
                'ignore' => __('Ignore'),
                'book' => __('Book'),
            ],
            'defaultAction' => 'ignore',
        ]);
    }

    private function getAccountSelectArray($accountGroups, $type)
    {
        return $accountGroups->where('type', $type)
            ->mapWithKeys(fn ($accountGroup) => [
                $accountGroup->title => $accountGroup->accounts()
                    ->mapWithKeys(fn ($account) => [$account->id => $account->title])
                    ->toArray(),
            ]);
    }

    private function validateRequest(Request $request): void
    {
        $request->validate([
            'period' => [
                'required',
                'integer',
                function ($attribute, $value, $fail) {
                    $period = Period::find($value);
                    if ($period == null) {
                        return $fail('Period does not exist.');
                    }
                    if ($period->state != 'open') {
                        return $fail('Period \''.$period->title.'\' is not open.');
                    }
                },
            ],
            'from' => [
                'required',
                'date',
            ],
            'to' => [
                'required',
                'date',
            ],
        ]);
    }

    public function store(Wallet $wallet, Request $request): RedirectResponse
    {
        $this->authorize('book-accounting-transactions-externally');

        $this->validateRequest($request);

        $period = Period::find($request->period);

        $preparedTransactions = collect($request->input('action', []))
            ->filter(
                fn ($v, $id) => $v == 'book'
                && ! empty($request->get('posting_text')[$id])
                && ! empty($request->get('debit_side')[$id])
                && ! empty($request->get('credit_side')[$id])
            )
            ->keys()
            ->map(fn ($id) => self::mapTransactionById($id, $request, $period))
            ->filter();

        $bookedTransactions = [];
        foreach ($preparedTransactions as $e) {
            try {
                $entrygroup = Entrygroup::createRaw($e['request']);
                $transaction = $e['transaction'];
                $transaction->booked = true;
                $transaction->external_id = $entrygroup->id;
                $transaction->save();
                $bookedTransactions[] = $transaction->id;
            } catch (Exception $e) {
                return redirect()->back()
                    ->withInput()
                    ->with('error', $e->getMessage());
            }
        }

        return redirect()
            ->route('accounting.webling.index', $wallet)
            ->with('info', __(':num transactions have been booked.', ['num' => count($bookedTransactions)]));
    }

    private static function mapTransactionById(int $id, Request $request, Period $period): ?array
    {
        $transaction = Transaction::find($id);
        if ($transaction != null) {
            return [
                'transaction' => $transaction,
                'request' => [
                    'properties' => [
                        'date' => $transaction->date,
                        'title' => $request->get('posting_text')[$id],
                    ],
                    'children' => [
                        'entry' => [
                            [
                                'properties' => [
                                    'amount' => $transaction->amount,
                                    'receipt' => $transaction->receipt_no,
                                ],
                                'links' => [
                                    'credit' => [
                                        $request->get('credit_side')[$id],
                                    ],
                                    'debit' => [
                                        $request->get('debit_side')[$id],
                                    ],
                                ],
                            ],
                        ],
                    ],
                    'parents' => [
                        $period->id,
                    ],
                ],
            ];
        }

        return null;
    }

    public function sync(Wallet $wallet, Request $request): RedirectResponse
    {
        $this->authorize('book-accounting-transactions-externally');

        $request->validate([
            'period' => [
                'required',
                'integer',
                function ($attribute, $value, $fail) {
                    $period = Period::find($value);
                    if ($period == null) {
                        return $fail('Period does not exist.');
                    }
                },
            ],
        ]);

        $synced = 0;
        try {
            $period = Period::find($request->period);
            $entryGroups = $period->entryGroups();
            $entryGroups = collect($entryGroups)
                // ->slice(0, 3)
                ->map(function ($entryGroup) {
                    $entryGroup->entries = $entryGroup->entries();

                    return $entryGroup;
                });

            foreach ($entryGroups as $entryGroup) {
                foreach ($entryGroup->entries as $entry) {
                    $transaction = Transaction::query()
                        ->whereDate('date', $entryGroup->date)
                        ->forWallet($wallet)
                        ->where('booked', false)
                        ->where('receipt_no', $entry->receipt)
                        ->first();
                    if ($transaction != null) {
                        $transaction->booked = true;
                        $transaction->external_id = $entryGroup->id;
                        $transaction->save();
                        $synced++;
                    }
                }
            }
        } catch (ConnectionException $e) {
            session()->now('error', $e->getMessage());
            // $transactions = [];
        }

        return redirect()
            ->route('accounting.webling.index', $wallet)
            ->with('info', __(':num transactions have been synchronized.', ['num' => $synced]));
    }
}
