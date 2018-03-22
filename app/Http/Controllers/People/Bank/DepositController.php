<?php

namespace App\Http\Controllers\People\Bank;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreDeposit;
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

    public function deposit() {
        $transactions = Transaction
                ::join('projects', function ($join) {
                    $join->on('projects.id', '=', 'transactions.transactionable_id')
                        ->where('transactionable_type', 'App\Project');
                })
                ->select('projects.name', 'value', 'transactions.created_at', 'transactions.user_id')
                ->orderBy('transactions.created_at', 'DESC')
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
            ->with('info', 'Added ' . $transaction->value . ' drachma to project \'' . $project->name . '\'.');
    }
}
