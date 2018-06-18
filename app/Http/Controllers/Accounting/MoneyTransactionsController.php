<?php

namespace App\Http\Controllers\Accounting;

use App\MoneyTransaction;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\Accounting\StoreTransaction;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

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

    /**
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function summary(Request $request)
    {
        //$this->authorize('list', MoneyTransaction::class);

        $validatedData = $request->validate([
            'month' => 'nullable|integer|min:1|max:12',
            'year' => 'nullable|integer|min:2017|max:' . Carbon::today()->year,
        ]);

        // Select date
        if (isset($request->month) && isset($request->year)) {
            $dateFrom = (new Carbon($request->year.'-'.$request->month.'-01'))->startOfMonth();
        } else {
            $dateFrom = Carbon::now()->startOfMonth();
        }
        $dateTo = (clone $dateFrom)->endOfMonth();

        // TODO: Probably define on more general location
        setlocale(LC_TIME, \App::getLocale());

        return view('accounting.transactions.summary', [
            'month' => $dateFrom->formatLocalized('%B %Y'),
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
