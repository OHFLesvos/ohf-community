<?php

namespace Modules\Accounting\Http\Controllers;

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
        $today = Carbon::today()->toDateString();
        $periods = Period::filtered('`from` < "'.$today.'" AND `to` > "'.$today.'"')->where('state', 'open');

        $period = $periods[0];
        $accountGroups = $period->accountGroups();

        $assetsSelect = $accountGroups->where('type', 'assets')
            ->mapWithKeys(function($accountGroup){ 
                return [ $accountGroup->title => $accountGroup->accounts()
                    ->mapWithKeys(function($account){ 
                        return [ $account->id => $account->title]; 
                    })->toArray() ]; 
                });

        $incomeSelect = $accountGroups->where('type', 'income')
            ->mapWithKeys(function($accountGroup){ 
                return [ $accountGroup->title => $accountGroup->accounts()
                    ->mapWithKeys(function($account){ 
                        return [ $account->id => $account->title]; 
                    })->toArray() ]; 
                });

        $expenseSelect = $accountGroups->where('type', 'expense')
            ->mapWithKeys(function($accountGroup){ 
                return [ $accountGroup->title => $accountGroup->accounts()
                    ->mapWithKeys(function($account){ 
                        return [ $account->id => $account->title]; 
                    })->toArray() ]; 
                });

        return view('accounting::webling', [
            'periods' => $periods,
            'assetsSelect' => $assetsSelect,
            'incomeSelect' => $incomeSelect,
            'expenseSelect' => $expenseSelect,
        ]);
    }

}
