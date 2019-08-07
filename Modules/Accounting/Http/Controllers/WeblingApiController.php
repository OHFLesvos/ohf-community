<?php

namespace Modules\Accounting\Http\Controllers;

use Modules\Accounting\Entities\MoneyTransaction;
use Modules\Accounting\Support\Webling\Entities\Period;
use Modules\Accounting\Support\Webling\Entities\Entrygroup;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;

use Carbon\Carbon;

class WeblingApiController extends Controller
{
    public function __construct()
    {
        // TODO find more generic place
        Carbon::setUtf8(true);
    }

    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        // TODO: Probably define on more general location
        setlocale(LC_TIME, \App::getLocale());

        $periods = Period::all()
            ->where('state', 'open')
            ->mapWithKeys(function($period){
                $months = MoneyTransaction::whereDate('date', '>=', $period->from)
                    ->whereDate('date', '<=', $period->to)
                    ->where('booked', false)
                    ->select(DB::raw('MONTH(date) as month'), DB::raw('YEAR(date) as year'))
                    ->groupBy(DB::raw('MONTH(date)'))
                    ->groupBy(DB::raw('YEAR(date)'))
                    ->orderBy('year', 'asc')
                    ->orderBy('month', 'asc')
                    ->get()
                    ->map(function($e){
                        $date = Carbon::createFromDate($e->year, $e->month, 1);
                        return (object) [
                            'transactions' => MoneyTransaction::whereDate('date', '>=', $date)
                                ->whereDate('date', '<=', (clone $date)->endOfMonth())
                                ->where('booked', false)
                                ->count(),
                            'date' => $date,
                        ];
                    });
                return [
                    $period->id => (object) [
                        'title' => $period->title,
                        'from' => $period->from,
                        'to' => $period->to,
                        'months' => $months,
                    ]
                ];
            });

        return view('accounting::webling.index', [
            'periods' => $periods,
        ]);
    }

    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function prepare(Request $request)
    {
        $this->validateRequest($request);

        $period = Period::find($request->period);
        
        $transactions = MoneyTransaction::whereDate('date', '>=', $request->from)
            ->whereDate('date', '<=', $request->to)
            ->whereDate('date', '>=', $period->from)
            ->whereDate('date', '<=', $period->to)
            ->where('booked', false)
            ->orderBy('date', 'asc')
            ->get();
        $hasTransactions = !$transactions->isEmpty();
        if ($hasTransactions) {
            $accountGroups = $period->accountGroups();
        }

        return view('accounting::webling.prepare', [
            'period' => $period,
            'from' => new Carbon($request->from),
            'to' => new Carbon($request->to),
            'transactions' => $transactions,
            'assetsSelect' => $hasTransactions ? $this->getAccountSelectArray($accountGroups, 'assets') : [],
            'incomeSelect' => $hasTransactions ? $this->getAccountSelectArray($accountGroups, 'income') : [],
            'expenseSelect' => $hasTransactions ? $this->getAccountSelectArray($accountGroups, 'expense') : [],
            'actions' => [
                'ignore' => __('app.ignore'),
                'book' => __('accounting::accounting.book'),
            ],
            'defaultAction' => 'ignore',
        ]);
    }

    private function getAccountSelectArray($accountGroups, $type)
    {
        return $accountGroups->where('type', $type)
            ->mapWithKeys(function($accountGroup){ 
                return [ $accountGroup->title => $accountGroup->accounts()
                    ->mapWithKeys(function($account){ 
                        return [ $account->id => $account->title]; 
                    })->toArray() ]; 
                });
    }

    private function validateRequest($request)
    {
        $request->validate([
            'period' => [
                'required',
                'integer',
                function($attribute, $value, $fail) {
                    $period = Period::find($value);
                    if ($period == null) {
                        return $fail('Period does not exist.');
                    }
                    if ($period->state != 'open') {
                        return $fail('Period \'' . $period->title . '\' is not open.');
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

    public function store(Request $request)
    {
        $this->validateRequest($request);

        $period = Period::find($request->period);

        $preparedTransactions = collect($request->input('action', []))
            ->filter(function($v, $id) use ($request){ 
                return $v == 'book'
                    && !empty($request->get('posting_text')[$id]) 
                    && !empty($request->get('debit_side')[$id])
                    && !empty($request->get('credit_side')[$id]); 
            })
            ->keys()
            ->map(function($id) use ($request, $period) {
                $transaction = MoneyTransaction::find($id);
                if ($transaction != null) {
                    return [
                        'transaction' => $transaction,
                        'request' => [
                            "properties" => [
                                "date" => $transaction->date,
                                "title" => $request->get('posting_text')[$id],
                            ],
                            "children" => [
                                "entry" => [
                                    [
                                        "properties" => [
                                            "amount" => $transaction->amount,
                                            "receipt" => $transaction->receipt_no,
                                        ],
                                        "links" => [
                                            "credit" => [
                                                $request->get('credit_side')[$id],
                                            ],
                                            "debit" => [
                                                $request->get('debit_side')[$id],
                                            ]
                                        ]
                                    ]
                                ]
                            ],
                            "parents" => [
                                $period->id,
                            ],
                        ]
                    ];
                }
                return null;
            })
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
                } catch (\Exception $e) {
                    return redirect()->back()
                        ->withInput()
                        ->with('error', $e->getMessage());
                }
            }

        return redirect()
            ->route('accounting.webling.index')
            ->with('info', __('accounting::accounting.num_transactions_booked', ['num' => count($bookedTransactions)]));
    }
}
