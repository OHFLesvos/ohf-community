<?php

namespace App\Http\Controllers\Accounting;

use App\MoneyTransaction;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\Accounting\StoreTransaction;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;

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
            'newReceiptNo' => optional(MoneyTransaction
                ::select(DB::raw('MAX(receipt_no) as val'))
                ->first())
                ->val + 1,
        ]);
    }

    private static function getBeneficiaries() {
        return MoneyTransaction
            ::select('beneficiary')
            ->groupBy('beneficiary')
            ->orderBy('beneficiary')
            ->get()
            ->pluck('beneficiary')
            ->unique()
            ->toArray();
    }

    private static function getProjects() {
        return MoneyTransaction
            ::select('project')
            ->groupBy('project')
            ->orderBy('project')
            ->get()
            ->pluck('project')
            ->unique()
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
        
        if (isset($request->receipt_picture)) {
            $transaction->receipt_picture = $request->file('receipt_picture')->store('public/accounting/receipts');
        }

        $transaction->save();

        return redirect()->route('accounting.transactions.index')
            ->with('info', __('accounting.transactions_registered'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\MoneyTransaction  $transaction
     * @return \Illuminate\Http\Response
     */
    public function show(MoneyTransaction $transaction)
    {
        $this->authorize('view', $transaction);

        return view('accounting.transactions.show', [
            'transaction' => $transaction,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\MoneyTransaction  $transaction
     * @return \Illuminate\Http\Response
     */
    public function edit(MoneyTransaction $transaction)
    {
        $this->authorize('update', $transaction);

        return view('accounting.transactions.edit', [
            'transaction' => $transaction,
            'beneficiaries' => self::getBeneficiaries(),
            'projects' => self::getProjects(),
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\MoneyTransaction  $transaction
     * @return \Illuminate\Http\Response
     */
    public function update(StoreTransaction $request, MoneyTransaction $transaction)
    {
        $this->authorize('update', $transaction);

        $transaction->date = $request->date;
        $transaction->type = $request->type;
        $transaction->amount = $request->amount;
        $transaction->beneficiary = $request->beneficiary;
        $transaction->receipt_no = $request->receipt_no;
        $transaction->project = $request->project;
        $transaction->description = $request->description;

        if (isset($request->receipt_picture)) {
            if ($transaction->receipt_picture != null) {
                Storage::delete($transaction->receipt_picture);
            }
            $transaction->receipt_picture = $request->file('receipt_picture')->store('public/accounting/receipts');
        }

        $transaction->save();

        return redirect()->route('accounting.transactions.show', $transaction)
        ->with('info', __('accounting.transactions_updated'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\MoneyTransaction  $transaction
     * @return \Illuminate\Http\Response
     */
    public function destroy(MoneyTransaction $transaction)
    {
        $this->authorize('delete', $transaction);

        if ($transaction->receipt_picture != null) {
            Storage::delete($transaction->receipt_picture);
        }

        $transaction->delete();

        return redirect()->route('accounting.transactions.index')
            ->with('info', __('accounting.transactions_deleted'));
    }

    /**
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function summary(Request $request)
    {
        $this->authorize('view-accounting-summary');

        // Select date range (month)
        $validatedData = $request->validate([
            'month' => 'nullable|regex:/[0-1][1-9]/',
            'year' => 'nullable|integer|min:2017|max:' . Carbon::today()->year,
        ]);
        if (isset($request->month) && isset($request->year)) {
            $dateFrom = (new Carbon($request->year.'-'.$request->month.'-01'))->startOfMonth();
        } else {
            $dateFrom = Carbon::now()->startOfMonth();
        }
        $dateTo = (clone $dateFrom)->endOfMonth();

        // TODO: Probably define on more general location
        setlocale(LC_TIME, \App::getLocale());

        return view('accounting.transactions.summary', [
            'monthName' => $dateFrom->formatLocalized('%B %Y'),
            'month' => $dateFrom->format('Y-m'),
            'months' => MoneyTransaction
                ::select(DB::raw('MONTH(date) as month'), DB::raw('YEAR(date) as year'))
                ->groupBy(DB::raw('MONTH(date)'))
                ->groupBy(DB::raw('YEAR(date)'))
                ->orderBy('year', 'desc')
                ->orderBy('month', 'desc')
                ->get()
                ->mapWithKeys(function($e){
                    $date = new Carbon($e->year.'-'.$e->month.'-01');
                    return [ $date->format('Y-m') => $date->formatLocalized('%B %Y') ];
                })
                ->toArray(),
            'incomeByProject' => MoneyTransaction
                ::select('project', DB::raw('SUM(amount) as sum'))
                ->whereDate('date', '>=', $dateFrom)
                ->whereDate('date', '<=', $dateTo)
                ->where('type', 'income')
                ->groupBy('project')
                ->orderBy('project')
                ->get(),
            'spendingByProject' => MoneyTransaction
                ::select('project', DB::raw('SUM(amount) as sum'))
                ->whereDate('date', '>=', $dateFrom)
                ->whereDate('date', '<=', $dateTo)
                ->where('type', 'spending')
                ->groupBy('project')
                ->orderBy('project')
                ->get(),
        ]);
    }
}
