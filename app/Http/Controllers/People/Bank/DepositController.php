<?php

namespace App\Http\Controllers\People\Bank;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\People\Bank\StoreDeposit;
use App\Project;
use App\Transaction;
use Carbon\Carbon;

class DepositController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show view for storing a new deposit and show past deposits.
     * 
     * @return \Illuminate\Http\Response
     */
    public function deposit() {
        $transactions = Transaction
                ::join('projects', function ($join) {
                    $join->on('projects.id', '=', 'transactions.transactionable_id')
                        ->where('transactionable_type', 'App\Project');
                })
                ->select('projects.name', 'value', 'transactions.created_at', 'transactions.user_id')
                ->orderBy('transactions.created_at', 'desc')
                ->paginate(10);
        
        return view('bank.deposit', [
            'projectList' => Project::orderBy('name')
                ->where('enable_in_bank', true)
                ->get()
                ->mapWithKeys(function($project){
                    return [$project->id => $project->name];
                }),
            'transactions' => $transactions,
        ]);
    }

    /**
     * Store a deposit.
     * 
     * @param  \App\Http\Requests\People\Bank\StoreDeposit  $request
     * @return \Illuminate\Http\Response
     */
    public function storeDeposit(StoreDeposit $request) {
        $project = Project::find($request->project);
        $transaction = new Transaction();
        $transaction->value = $request->value;
		$date = new Carbon($request->date);
		if (!$date->isToday()) {
			$transaction->created_at = $date->endOfDay();
		}
        $project->transactions()->save($transaction);

        return redirect()->route('bank.deposit')
            ->with('info', __('people.deposited_n_drachma_to_project', [ 'amount' => $transaction->value, 'project' => $project->name ]));
    }
}
