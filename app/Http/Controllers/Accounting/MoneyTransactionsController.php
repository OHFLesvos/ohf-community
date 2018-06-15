<?php

namespace App\Http\Controllers\Accounting;

use App\MoneyTransaction;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\Accounting\StoreTransaction;

class MoneyTransactionsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->authorize('list', MoneyTransaction::class);

        return view('accounting.transactions.index', [
            'transactions' => MoneyTransaction
                ::orderBy('date', 'DESC')
                ->orderBy('created_at', 'DESC')
                ->paginate(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->authorize('create', MoneyTransaction::class);

        return view('accounting.transactions.create', [
            'beneficiaries' => self::getBeneficiaries(),
            'projects' => self::getProjects(),
        ]);
    }

    private static function getBeneficiaries() {
        return MoneyTransaction
            ::select('beneficiary')
            ->groupBy('beneficiary')
            ->orderBy('beneficiary')
            ->get()
            ->pluck('beneficiary')
            ->toArray();
    }

    private static function getProjects() {
        return MoneyTransaction
            ::select('project')
            ->groupBy('project')
            ->orderBy('project')
            ->get()
            ->pluck('project')
            ->toArray();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\Accounting\StoreTransaction  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreTransaction $request)
    {
        $this->authorize('create', MoneyTransaction::class);

        $transaction = new MoneyTransaction();
        $transaction->date = $request->date;
        $transaction->type = $request->type;
        $transaction->amount = $request->amount;
        $transaction->beneficiary = $request->beneficiary;
        $transaction->receipt_no = $request->receipt_no;
        $transaction->project = $request->project;
        $transaction->description = $request->description;
        $transaction->save();

        return redirect()->route('accounting.transactions.index')
            ->with('info', __('accounting.transactions_registered'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\MoneyTransaction  $moneyTransaction
     * @return \Illuminate\Http\Response
     */
    public function show(MoneyTransaction $transaction)
    {
        $this->authorize('view', $transaction);

        return view('accounting.transactions.show', [
            'transaction' => $transaction, // ??
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\MoneyTransaction  $moneyTransaction
     * @return \Illuminate\Http\Response
     */
    public function edit(MoneyTransaction $moneyTransaction)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\MoneyTransaction  $moneyTransaction
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, MoneyTransaction $moneyTransaction)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\MoneyTransaction  $moneyTransaction
     * @return \Illuminate\Http\Response
     */
    public function destroy(MoneyTransaction $moneyTransaction)
    {
        //
    }
}
