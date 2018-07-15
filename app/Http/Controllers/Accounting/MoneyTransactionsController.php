<?php

namespace App\Http\Controllers\Accounting;

use App\MoneyTransaction;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\Accounting\StoreTransaction;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use \Gumlet\ImageResize;
use Illuminate\Support\Facades\Config;
use Illuminate\Validation\Rule;

class MoneyTransactionsController extends Controller
{
    private static $filterColumns = [
        'type',
        'project',
        'beneficiary',
        'receipt_no'
    ];

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $this->authorize('list', MoneyTransaction::class);

        $validatedData = $request->validate([
            'date_start' => [
                'nullable',
                'date',
                'before_or_equal:' . Carbon::today(),
            ],
            'date_end' => [
                'nullable',
                'date',
                'before_or_equal:' . Carbon::today(),
            ],
            'type' => [
                'nullable',
                Rule::in(['income', 'spending']),
            ],
            'month' => 'nullable|regex:/[0-1]?[1-9]/',
            'year' => 'nullable|integer|min:2017|max:' . Carbon::today()->year,
            'sortColumn' => 'nullable|in:date,created_at,project,beneficiary,receipt_no',
            'sortOrder' => 'nullable|in:asc,desc',
        ]);

        $sortColumns = [
            'date' => __('app.date'),
            'project' => __('app.project'),
            'beneficiary'=> __('accounting.beneficiary'),
            'receipt_no' => __('accounting.receipt'),
            'created_at' => __('app.registered')
        ];
        $sortColumn = session('accounting.sortColumn', 'date');
        $sortOrder = session('accounting.sortOrder', 'desc');
        if (isset($request->sortColumn)) {
            $sortColumn = $request->sortColumn;
            session(['accounting.sortColumn' => $sortColumn]);
        }
        if (isset($request->sortOrder)) {
            $sortOrder = $request->sortOrder;
            session(['accounting.sortOrder' => $sortOrder]);
        }

        if ($request->query('reset_filter') != null) {
            session(['accounting.filter' => []]);
        }
        $filter = session('accounting.filter', []);
        foreach (self::$filterColumns as $col) {
            if (!empty($request->filter[$col])) {
                $filter[$col] = $request->filter[$col];
            }
        }
        if (!empty($request->filter['date_start'])) {
            $filter['date_start'] = $request->filter['date_start'];
        }
        if (!empty($request->filter['date_end'])) {
            $filter['date_end'] = $request->filter['date_end'];
        }
        session(['accounting.filter' => $filter]);

        // Apply filter to eloquent query
        $query = MoneyTransaction
            ::orderBy($sortColumn, $sortOrder)
            ->orderBy('created_at', 'DESC');
        foreach (self::$filterColumns as $col) {
            if (!empty($filter[$col])) {
                // TODO: use 'like' % ... % for project and beneficiary 
                $query->where($col, $filter[$col]);
            }
        }
        if (!empty($filter['date_start'])) {
            $query->whereDate('date', '>=', $filter['date_start']);
        }
        if (!empty($filter['date_end'])) {
            $query->whereDate('date', '<=', $filter['date_end']);
        }

        return view('accounting.transactions.index', [
            'transactions' => $query->paginate(100),
            'filter' => $filter,
            'sortColumns' => $sortColumns,
            'sortColumn' => $sortColumn,
            'sortOrder' => $sortOrder,
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
            'descriptions' => self::getDescriptions(),
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

    private static function getDescriptions() {
        return MoneyTransaction
            ::select('description')
            ->groupBy('description')
            ->orderBy('description')
            ->get()
            ->pluck('description')
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
            // Resize image
            $image = new ImageResize($request->file('receipt_picture')->getRealPath());
            $image->resizeToBestFit(800, 800);
            $image->save($request->file('receipt_picture')->getRealPath());

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
            'descriptions' => self::getDescriptions(),
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

        if (isset($request->remove_receipt_picture)) {
            $transaction->receipt_picture = null;
            Storage::delete($transaction->receipt_picture);
        }
        if (isset($request->receipt_picture)) {
            if ($transaction->receipt_picture != null) {
                Storage::delete($transaction->receipt_picture);
            }

            // Resize image
            $image = new ImageResize($request->file('receipt_picture')->getRealPath());
            $image->resizeToBestFit(800, 800);
            $image->save($request->file('receipt_picture')->getRealPath());

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

        $currentMonth = Carbon::now()->startOfMonth();

        // Select date range (month)
        $validatedData = $request->validate([
            'month' => 'nullable|regex:/[0-1][1-9]/',
            'year' => 'nullable|integer|min:2017|max:' . Carbon::today()->year,
        ]);
        if (isset($request->month) && isset($request->year)) {
            $dateFrom = (new Carbon($request->year.'-'.$request->month.'-01'))->startOfMonth();
        } else {
            $dateFrom = $currentMonth;
        }
        $dateTo = (clone $dateFrom)->endOfMonth();

        // TODO: Probably define on more general location
        setlocale(LC_TIME, \App::getLocale());

        $incomeByProject = MoneyTransaction
            ::select('project', DB::raw('SUM(amount) as sum'))
            ->whereDate('date', '>=', $dateFrom)
            ->whereDate('date', '<=', $dateTo)
            ->where('type', 'income')
            ->groupBy('project')
            ->orderBy('project')
            ->get();

        $spendingByProject = MoneyTransaction
            ::select('project', DB::raw('SUM(amount) as sum'))
            ->whereDate('date', '>=', $dateFrom)
            ->whereDate('date', '<=', $dateTo)
            ->where('type', 'spending')
            ->groupBy('project')
            ->orderBy('project')
            ->get();
    
        // Calculate wallet
        $previousIncome = optional(MoneyTransaction
            ::select(DB::raw('SUM(amount) as sum'))
            ->whereDate('date', '<', $dateFrom)
            ->where('type', 'income')
            ->first())
            ->sum;
        $previousSpending = optional(MoneyTransaction
            ::select(DB::raw('SUM(amount) as sum'))
            ->whereDate('date', '<', $dateFrom)
            ->where('type', 'spending')
            ->first())
            ->sum;

        $previousWallet = $previousIncome - $previousSpending;
        $wallet = $previousWallet + $incomeByProject->sum('sum') - $spendingByProject->sum('sum');

        $months = MoneyTransaction
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
            ->prepend($currentMonth->formatLocalized('%B %Y'), $currentMonth->format('Y-m'))
            ->toArray();

        return view('accounting.transactions.summary', [
            'monthDate' => $dateFrom,
            'months' => $months,
            'incomeByProject' => $incomeByProject,
            'spendingByProject' => $spendingByProject,
            'wallet' => $wallet,
        ]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function export()
    {
        $this->authorize('list', MoneyTransaction::class);

        // TODO: Probably define on more general location
        setlocale(LC_TIME, \App::getLocale());

        $months = MoneyTransaction
            ::select(DB::raw('MONTH(date) as month'), DB::raw('YEAR(date) as year'))
            ->groupBy(DB::raw('MONTH(date)'))
            ->groupBy(DB::raw('YEAR(date)'))
            ->orderBy('year', 'asc')
            ->orderBy('month', 'asc')
            ->get()
            ->map(function($e){
                return (new Carbon($e->year.'-'.$e->month.'-01'))->startOfMonth();
            })
            ->toArray();

        \Excel::create(Config::get('app.name') . ' ' . __('accounting.accounting') . ' (' . Carbon::now()->toDateString() . ')', function($excel) use($months) {
            foreach($months as $month) {
                $excel->sheet($month->formatLocalized('%B %Y'), function($sheet) use ($month) {
                    $dateFrom = $month;
                    $dateTo = (clone $dateFrom)->endOfMonth();

                    $transactions = MoneyTransaction
                        ::orderBy('date', 'DESC')
                        ->orderBy('created_at', 'DESC')
                        ->whereDate('date', '>=', $dateFrom)
                        ->whereDate('date', '<=', $dateTo)                        
                        ->get();

                    $sheet->setOrientation('landscape');
                    $sheet->freezeFirstRow();
                    $sheet->loadView('accounting.transactions.export', [
                        'transactions' => $transactions,
                    ]);
                    $sheet->getStyle('B')->getNumberFormat()->setFormatCode('#,##0.00');
                    $sheet->getStyle('C')->getNumberFormat()->setFormatCode('#,##0.00');

                    //$sheet->setCellValue($sumCell, '=SUM(B2:B' . (count($transactions) + 1) . ')');
                    $sumCell = 'B' . (count($transactions) + 3);
                    $sheet->setCellValue($sumCell, $transactions->where('type', 'income')->sum('amount'));
                    $sheet->getStyle($sumCell)->getFont()->setUnderline(\PHPExcel_Style_Font::UNDERLINE_DOUBLEACCOUNTING);

                    $sumCell = 'C' . (count($transactions) + 3);
                    $sheet->setCellValue($sumCell, $transactions->where('type', 'spending')->sum('amount'));
                    $sheet->getStyle($sumCell)->getFont()->setUnderline(\PHPExcel_Style_Font::UNDERLINE_DOUBLEACCOUNTING);

                });
                $excel->getActiveSheet()->setAutoFilter(
                    $excel->getActiveSheet()->calculateWorksheetDimension()
                );
            }
        })->export('xlsx');
    }

}
