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
        echo "<pre>";
        print_r($request->all());
        echo "</pre>";
    }
}
