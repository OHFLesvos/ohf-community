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
        // TODO What should be done at end of year?
        $today = Carbon::today()->toDateString();
        $periods = Period::filtered('`from` < "'.$today.'" AND `to` > "'.$today.'"')->where('state', 'open');

        if (!$periods->isEmpty()) {
            $period = $periods[0];

            $transactions = MoneyTransaction::whereDate('date', '>=', $period->from)
                ->whereDate('date', '<=', $period->to)
                ->where('booked', false)
                ->orderBy('date', 'asc')
                ->get();

            $accountGroups = $period->accountGroups();
    
            $assetsSelect = $this->getAccountSelectArray($accountGroups, 'assets');
            $incomeSelect = $this->getAccountSelectArray($accountGroups, 'income');
            $expenseSelect = $this->getAccountSelectArray($accountGroups, 'expense');

            return view('accounting::webling.index', [
                'period' => $period,
                'assetsSelect' => $assetsSelect,
                'incomeSelect' => $incomeSelect,
                'expenseSelect' => $expenseSelect,
                'transactions' => $transactions,
                'actions' => [
                    'ignore' => __('app.ignore'),
                    'book' => __('accounting::accounting.book'),
                ],
                'defaultAction' => 'ignore',
            ]);
        }
        return view('accounting::webling.index', [ ]);
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
