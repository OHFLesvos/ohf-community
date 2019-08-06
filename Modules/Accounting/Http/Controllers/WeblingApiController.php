<?php

namespace Modules\Accounting\Http\Controllers;

use Modules\Accounting\Entities\MoneyTransaction;
use Modules\Accounting\Support\Webling\Entities\Period;
use Modules\Accounting\Support\Webling\Entities\AccountGroup;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;

use Carbon\Carbon;

class WeblingApiController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        // $today = Carbon::today()->toDateString();
        // $periods = Period::filtered('`from` < "'.$today.'" AND `to` > "'.$today.'"')->where('state', 'open');
        $periods = Period::all()->where('state', 'open')
            ->mapWithKeys(function($period){
                $transactions = MoneyTransaction::whereDate('date', '>=', $period->from)
                    ->whereDate('date', '<=', $period->to)
                    ->where('booked', false)
                    ->orderBy('date', 'asc')
                    ->get();
                $hasTransactions = !$transactions->isEmpty();
                if ($hasTransactions) {
                    $accountGroups = $period->accountGroups();
                }
                return [
                    $period->id => (object) [
                        'title' => $period->title,
                        'from' => $period->from,
                        'to' => $period->to,
                        'transactions' => $transactions,
                        'assetsSelect' => $hasTransactions ? $this->getAccountSelectArray($accountGroups, 'assets') : [],
                        'incomeSelect' => $hasTransactions ? $this->getAccountSelectArray($accountGroups, 'income') : [],
                        'expenseSelect' => $hasTransactions ? $this->getAccountSelectArray($accountGroups, 'expense') : [],
                    ]
                ];
            });

        return view('accounting::webling.index', [
            'periods' => $periods,
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

    public function store(Request $request)
    {
        $data = collect($request->input('action', []))
            ->mapWithKeys(function($e, $period_id) use ($request) {
                return [ $period_id => collect($e)
                    ->filter(function($v, $id) use ($request, $period_id){ 
                        return $v == 'book' 
                            && !empty($request->get('posting_text')[$period_id][$id]) 
                            && !empty($request->get('debit_side')[$period_id][$id])
                            && !empty($request->get('credit_side')[$period_id][$id]); 
                    })
                    ->keys()
                    ->mapWithKeys(function($id) use ($request, $period_id) {
                        return [ $id => [
                            'posting_text' => $request->get('posting_text')[$period_id][$id],
                            'debit_side' => $request->get('debit_side')[$period_id][$id],
                            'credit_side' => $request->get('credit_side')[$period_id][$id],
                        ]];
                    })
                ];
            })
            ->filter(function($e){ 
                return !$e->isEmpty(); 
            })
            ->flatMap(function($transactions, $period_id){
                $period = Period::find($period_id);
                if ($period != null) {
                    return $transactions->map(function($transaction_data, $transaction_id) use($period_id) {
                        $transaction = MoneyTransaction::find($transaction_id);
                        if ($transaction != null) {
                            return [
                                "properties" => [
                                    "date" => $transaction->date,
                                    "title" => $transaction_data['posting_text'],
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
                                                    $transaction_data['credit_side'],
                                                ],
                                                "debit" => [
                                                    $transaction_data['debit_side'],
                                                ]
                                            ]
                                        ]
                                    ]
                                ],
                                "parents" => [
                                    $period_id,
                                ],
                            ];
                        }
                        return null;
                    })
                    ->filter();
                }
                return null;
            })
            ->filter()
            ->toArray();
        echo "<pre>";
        print_r($data);
        echo "</pre>";
    }
}
