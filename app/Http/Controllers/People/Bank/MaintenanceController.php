<?php

namespace App\Http\Controllers\People\Bank;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use App\Person;
use App\Transaction;
use Illuminate\Support\Facades\DB;

class MaintenanceController extends Controller
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

    const MONTHS_NO_TRANSACTIONS_SINCE = 2;

    function maintenance() {
        return view('bank.maintenance', [
            'months_no_transactions_since' => self::MONTHS_NO_TRANSACTIONS_SINCE,
            'people_without_transactions_since' => $this->getPeopleWithoutTransactionsSince(self::MONTHS_NO_TRANSACTIONS_SINCE),
            'people_without_transactions_ever' => $this->getPeopleWithoutTransactionsEver(),
            'people_without_number' => $this->getPeopleWithoutNumber(),
            'num_people' => Person::count(),
        ]);
    }

    /**
     * @param int $months number of months
     * @return int
     */
    private function getPeopleWithoutTransactionsSince($months): int
    {
        return Transaction::groupBy('transactionable_id')
            ->having(DB::raw('max(transactions.created_at)'), '<=', Carbon::today()->subMonth($months))
            ->where('worker', false)
            ->join('persons', function ($join) {
                $join->on('persons.id', '=', 'transactions.transactionable_id')
                    ->where('transactionable_type', 'App\Person')
                    ->whereNull('deleted_at');
            })
            ->get()
            ->count();
    }

    /**
     * @return int
     */
    private function getPeopleWithoutTransactionsEver(): int
    {
        return Person::leftJoin('transactions', function ($join) {
            $join->on('persons.id', '=', 'transactions.transactionable_id')
                ->where('transactionable_type', 'App\Person')
                ->whereNull('deleted_at');
            })
            ->whereNull('transactions.id')
            ->whereNull('boutique_coupon')
            ->whereNull('diapers_coupon')
            ->where('worker', false)
            ->get()
            ->count();
    }

    /**
     * @return int
     */
    private function getPeopleWithoutNumber(): int
    {
        return Person::whereNull('police_no')
            ->whereNull('case_no')
            ->whereNull('medical_no')
            ->whereNull('registration_no')
            ->whereNull('section_card_no')
            ->whereNull('temp_no')
            ->where('worker', false)
            ->count();
    }

    function updateMaintenance(Request $request) {
	    $cnt = 0;
        if (isset($request->cleanup_no_transactions_since)) {
            $cnt += Person::destroy(Transaction::groupBy('transactionable_id')
                ->having(DB::raw('max(transactions.created_at)'), '<=', Carbon::today()->subMonth(self::MONTHS_NO_TRANSACTIONS_SINCE))
                ->having('transactionable_type', 'App\Person')
                ->get()
                ->map(function($item){
                    return $item->transactionable_id;
                })
                ->toArray()
            );
        }
        if (isset($request->cleanup_no_transactions_ever)) {
            $cnt += Person::destroy(Person::leftJoin('transactions', function($join){
                $join->on('persons.id', '=', 'transactions.transactionable_id')
                    ->where('transactionable_type', 'App\Person')
                    ->whereNull('deleted_at');
                })
                ->whereNull('transactions.id')
                ->whereNull('boutique_coupon')
                ->whereNull('diapers_coupon')
                ->where('worker', false)
                ->select('persons.id')
                ->get()
                ->map(function($item){
                    return $item->id;
                })
                ->toArray()
            );
        }
        if (isset($request->cleanup_no_number)) {
            $cnt +=  Person::whereNull('police_no')
                ->whereNull('case_no')
                ->whereNull('medical_no')
                ->whereNull('registration_no')
                ->whereNull('section_card_no')
                ->whereNull('temp_no')
                ->where('worker', false)
                ->delete();
        }
        return redirect()->route('bank.withdrawal')
            ->with('info', 'Removed ' . $cnt . ' records.');
    }

}
