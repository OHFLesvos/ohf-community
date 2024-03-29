<?php

namespace App\Http\Controllers\Accounting\API;

use App\Http\Controllers\Controller;
use App\Models\Accounting\Transaction;
use App\Models\Accounting\Wallet;
use App\Support\Accounting\Webling\Entities\Entrygroup;
use App\Support\Accounting\Webling\Entities\Period;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Collection;

class WeblingApiController extends Controller
{
    public function periods(Wallet $wallet): JsonResponse
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

        return response()->json([
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

    public function prepare(Wallet $wallet, Request $request): JsonResponse
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

        return response()->json([
            'wallet' => $wallet,
            'period' => $period,
            'from' => new Carbon($request->from),
            'to' => new Carbon($request->to),
            'transactions' => $transactions->map(fn (Transaction $t) => [
                'id' => $t->id,
                'category_name' => $t->category->name,
                'project_name' => $t->project?->name,
                'date' => $t->date,
                'type' => $t->type,
                'amount' => $t->amount,
                'receipt_no' => $t->receipt_no,
                'description' => $t->description,
                'booked' => $t->booked,
                'external_id' => $t->external_id,
                'controlled_at' => $t->controlled_at,
            ]),
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

    public function store(Wallet $wallet, Request $request): JsonResponse
    {
        $this->authorize('book-accounting-transactions-externally');

        $this->validateRequest($request);
        $request->validate([
            'transactions' => [
                'array',
            ],
            'transactions.*.id' => [
                'integer',
                'required',
            ],
            'transactions.*.posting_text' => [
                'required',
            ],
            'transactions.*.debit_side' => [
                'integer',
                'required',
            ],
            'transactions.*.credit_side' => [
                'integer',
                'required',
            ],
        ]);

        $period = Period::find($request->period);

        $preparedTransactions = collect($request->input('transactions', []))
            ->filter(
                fn ($t) => $t['action'] == 'book'
                && filled($t['posting_text'])
                && filled($t['debit_side'])
                && filled($t['credit_side'])
            )
            ->map(fn ($t) => self::mapTransactionById($t, $period))
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
                return response()->json([
                    'error' => $e->getMessage(),
                ], Response::HTTP_INTERNAL_SERVER_ERROR);
            }
        }

        return response()->json([
            'info' => __(':num transactions have been booked.', ['num' => count($bookedTransactions)]),
        ]);
    }

    private static function mapTransactionById(array $preparedTransaction, Period $period): ?array
    {
        $transaction = Transaction::find($preparedTransaction['id']);
        if ($transaction != null) {
            return [
                'transaction' => $transaction,
                'request' => [
                    'properties' => [
                        'date' => $transaction->date,
                        'title' => $preparedTransaction['posting_text'],
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
                                        $preparedTransaction['credit_side'],
                                    ],
                                    'debit' => [
                                        $preparedTransaction['debit_side'],
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
}
