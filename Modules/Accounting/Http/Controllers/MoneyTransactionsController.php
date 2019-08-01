<?php

namespace Modules\Accounting\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Export\ExportableActions;

use Modules\Accounting\Http\Requests\StoreTransaction;
use Modules\Accounting\Entities\MoneyTransaction;
use Modules\Accounting\Entities\SignedMoneyTransaction;
use Modules\Accounting\Exports\MoneyTransactionsExport;
use Modules\Accounting\Exports\MoneyTransactionsMonthsExport;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Config;
use Illuminate\Validation\Rule;

use Carbon\Carbon;

use \Gumlet\ImageResize;

class MoneyTransactionsController extends Controller
{
    use ExportableActions;

    public function __construct()
    {
        Carbon::setUtf8(true);
    }

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
            'sortColumn' => 'nullable|in:date,created_at,category,project,beneficiary,receipt_no',
            'sortOrder' => 'nullable|in:asc,desc',
        ]);

        $sortColumns = [
            'date' => __('app.date'),
            'category' => __('app.category'),
            'project' => __('app.project'),
            'beneficiary'=> __('accounting::accounting.beneficiary'),
            'receipt_no' => __('accounting::accounting.receipt'),
            'created_at' => __('app.registered')
        ];
        $sortColumn = session('accounting.sortColumn', 'created_at');
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
        foreach (Config::get('accounting.filter_columns') as $col) {
            if (!empty($request->filter[$col])) {
                $filter[$col] = $request->filter[$col];
            } else if (isset($request->filter)) {
                unset($filter[$col]);
            }
        }
        if (!empty($request->filter['date_start'])) {
            $filter['date_start'] = $request->filter['date_start'];
        } else if (isset($request->filter)) {
            unset($filter['date_start']);
        }
        if (!empty($request->filter['date_end'])) {
            $filter['date_end'] = $request->filter['date_end'];
        } else if (isset($request->filter)) {
            unset($filter['date_end']);
        }
        session(['accounting.filter' => $filter]);

        $query = self::createIndexQuery($filter, $sortColumn, $sortOrder);
        
        // Get results
        $transactions = $query->paginate(250);

        // Single receipt no. query
        if ($transactions->count() == 1 && !empty($filter['receipt_no'])) {
            session(['accounting.filter' => []]);
            return redirect()->route('accounting.transactions.show', $transactions->first());
        }

        return view('accounting::transactions.index', [
            'transactions' => $transactions,
            'filter' => $filter,
            'sortColumns' => $sortColumns,
            'sortColumn' => $sortColumn,
            'sortOrder' => $sortOrder,
            'beneficiaries' => self::getBeneficiaries(),
            'categories' => self::getCategories(),
            'projects' => self::getProjects(),
        ]);
    }

    private static function createIndexQuery($filter, $sortColumn, $sortOrder) {
        $query = MoneyTransaction
            ::orderBy($sortColumn, $sortOrder)
            ->orderBy('created_at', 'DESC');
        self::applyFilterToQuery($filter, $query);
        return $query;
    }

    public static function applyFilterToQuery($filter, &$query, $skipDates = false) {
        foreach (Config::get('accounting.filter_columns') as $col) {
            if (!empty($filter[$col])) {
                if ($col == 'today') {
                    $query->whereDate('created_at', Carbon::today()); 
                } else if ($col == 'no_receipt') {  
                    $query->where(function($query){
                        $query->whereNull('receipt_no');
                        $query->orWhereNull('receipt_picture');
                    }); 
                } else if ($col == 'beneficiary' || $col == 'description') {
                    $query->where($col, 'like', '%' . $filter[$col] . '%');
                } else {
                    $query->where($col, $filter[$col]);
                }
            }
        }
        if (!$skipDates) {
            if (!empty($filter['date_start'])) {
                $query->whereDate('date', '>=', $filter['date_start']);
            }
            if (!empty($filter['date_end'])) {
                $query->whereDate('date', '<=', $filter['date_end']);
            }
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->authorize('create', MoneyTransaction::class);

        return view('accounting::transactions.create', [
            'beneficiaries' => self::getBeneficiaries(),
            'categories' => self::getCategories(),
            'projects' => self::getProjects(),
            'newReceiptNo' => MoneyTransaction::getNextFreeReceiptNo(),
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

    private static function getCategories() {
        return MoneyTransaction
            ::select('category')
            ->groupBy('category')
            ->orderBy('category')
            ->get()
            ->pluck('category')
            ->unique()
            ->toArray();
    }

    private static function getProjects() {
        return MoneyTransaction
            ::select('project')
            ->where('project', '!=', null)
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
     * @param  \Modules\Accounting\Http\Requests\StoreTransaction  $request
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
        $transaction->category = $request->category;
        $transaction->project = $request->project;
        $transaction->description = $request->description;
        $transaction->remarks = $request->remarks;
        $transaction->wallet_owner = $request->wallet_owner;
        
        if (isset($request->receipt_picture)) {
            // Resize image
            $image = new ImageResize($request->file('receipt_picture')->getRealPath());
            $image->resizeToBestFit(800, 800);
            $image->save($request->file('receipt_picture')->getRealPath());

            $transaction->receipt_picture = $request->file('receipt_picture')->store('public/accounting/receipts');
        }

        $transaction->save();

        return redirect()
            ->route($request->submit == 'save_and_continue' ? 'accounting.transactions.create' : 'accounting.transactions.index')
            ->with('info', __('accounting::accounting.transactions_registered'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \Modules\Accounting\Entities\MoneyTransaction  $transaction
     * @return \Illuminate\Http\Response
     */
    public function show(MoneyTransaction $transaction)
    {
        $this->authorize('view', $transaction);

        $sortColumn = session('accounting.sortColumn', 'created_at');
        $sortOrder = session('accounting.sortOrder', 'desc');
        $filter = session('accounting.filter', []);
        $query = self::createIndexQuery($filter, $sortColumn, $sortOrder);
        // TODO: can this be optimized, e.g. with a cursor??
        $res = $query->select('id')->get()->pluck('id')->toArray();
        $prev_id = null;
        $next_id = null;
        $cnt = count($res);
        for ($i = 0; $i < $cnt; $i++) {
            $prev_id = $i > 0 ? $res[$i - 1] : null;
            $next_id = $i < $cnt - 1 ? $res[$i + 1] : null;
            if ($res[$i] == $transaction->id) {
                break;
            }
        }

        return view('accounting::transactions.show', [
            'transaction' => $transaction,
            'prev_id' => $prev_id,
            'next_id' => $next_id,
        ]);
    }

    public function snippet(MoneyTransaction $transaction)
    {
        $this->authorize('view', $transaction);

        return view('accounting::transactions.snippet', [
            'transaction' => $transaction,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \Modules\Accounting\Entities\MoneyTransaction  $transaction
     * @return \Illuminate\Http\Response
     */
    public function edit(MoneyTransaction $transaction)
    {
        $this->authorize('update', $transaction);

        return view('accounting::transactions.edit', [
            'transaction' => $transaction,
            'beneficiaries' => self::getBeneficiaries(),
            'categories' => self::getCategories(),
            'projects' => self::getProjects(),
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Modules\Accounting\Entities\MoneyTransaction  $transaction
     * @return \Illuminate\Http\Response
     */
    public function update(StoreTransaction $request, MoneyTransaction $transaction)
    {
        $this->authorize('update', $transaction);

        $transaction->date = $request->date;
        $transaction->type = $request->type;
        $transaction->amount = $request->amount;
        $transaction->beneficiary = $request->beneficiary;
        $transaction->category = $request->category;
        $transaction->project = $request->project;
        $transaction->description = $request->description;
        $transaction->remarks = $request->remarks;
        $transaction->wallet_owner = $request->wallet_owner;

        if (isset($request->remove_receipt_picture)) {
            Storage::delete($transaction->receipt_picture);
            $transaction->receipt_picture = null;
        }
        else if (isset($request->receipt_picture)) {
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

        return redirect()
            ->route('accounting.transactions.index')
            ->with('info', __('accounting::accounting.transactions_updated'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \Modules\Accounting\Entities\MoneyTransaction  $transaction
     * @return \Illuminate\Http\Response
     */
    public function destroy(MoneyTransaction $transaction)
    {
        $this->authorize('delete', $transaction);

        $transaction->delete();

        return redirect()
            ->route('accounting.transactions.index')
            ->with('info', __('accounting::accounting.transactions_deleted'));
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
            'month' => 'nullable|regex:/[0-1][0-9]/',
            'year' => 'nullable|integer|min:2000|max:' . Carbon::today()->year,
        ]);
        if (isset($request->month) && isset($request->year)) {
            $dateFrom = (new Carbon($request->year.'-'.$request->month.'-01'))->startOfMonth();
        } else {
            $dateFrom = $currentMonth;
        }
        $dateTo = (clone $dateFrom)->endOfMonth();

        // TODO: Probably define on more general location
        setlocale(LC_TIME, \App::getLocale());

        $revenueByCategory = SignedMoneyTransaction
            ::select('category', DB::raw('SUM(amount) as sum'))
            ->whereDate('date', '>=', $dateFrom)
            ->whereDate('date', '<=', $dateTo)
            ->groupBy('category')
            ->orderBy('category')
            ->get();

        $revenueByProject = SignedMoneyTransaction
            ::select('project', DB::raw('SUM(amount) as sum'))
            ->whereDate('date', '>=', $dateFrom)
            ->whereDate('date', '<=', $dateTo)
            ->groupBy('project')
            ->orderBy('project')
            ->get();

        // Calculate wallet
        $previousRevenue = optional(SignedMoneyTransaction
            ::select(DB::raw('SUM(amount) as sum'))
            ->whereDate('date', '<', $dateFrom)
            ->first())
            ->sum;

        $wallet = $previousRevenue + $revenueByCategory->sum('sum');

        $spending = optional(MoneyTransaction
            ::select(DB::raw('SUM(amount) as sum'))
            ->whereDate('date', '>=', $dateFrom)
            ->whereDate('date', '<=', $dateTo)
            ->where('type', 'spending')
            ->first())
            ->sum;

        $income = optional(MoneyTransaction
            ::select(DB::raw('SUM(amount) as sum'))
            ->whereDate('date', '>=', $dateFrom)
            ->whereDate('date', '<=', $dateTo)
            ->where('type', 'income')
            ->first())
            ->sum;

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

        return view('accounting::transactions.summary', [
            'monthDate' => $dateFrom,
            'months' => $months,
            'revenueByCategory' => $revenueByCategory,
            'revenueByProject' => $revenueByProject,
            'wallet' => $wallet,
            'spending' => $spending,
            'income' => $income,
        ]);
    }

    function exportAuthorize()
    {
        $this->authorize('list', MoneyTransaction::class);
    }

    function exportView(): string
    {
        return 'accounting::transactions.export';
    }

    function exportViewArgs(): array
    {
        $filter = session('accounting.filter', []);
        return [ 
            'groupings' => [
                'none' => __('app.none'),
                'monthly' => __('app.monthly'),
            ],
            'grouping' => 'none',
            'selections' => !empty($filter) ? [
                'all' => __('app.all_records'),
                'filtered' => __('app.selected_records_according_to_current_filter')
            ] : null,
            'selection' => 'all',
        ];
    }

    function exportValidateArgs(): array
    {
        return [
            'grouping' => [
                'required', 
                Rule::in(['none', 'monthly']),
            ],
            'selection' => [
                'nullable', 
                Rule::in(['all', 'filtered']),
            ],
        ];
    }

    function exportFilename(Request $request): string
    {
        return Config::get('app.name') . ' ' . __('accounting::accounting.accounting') . ' (' . Carbon::now()->toDateString() . ')';
    }

    function exportExportable(Request $request)
    {
        $filter = $request->selection == 'filtered' ? session('accounting.filter', []) : [];
        if ($request->grouping == 'monthly') {
            return new MoneyTransactionsMonthsExport($filter);
        }
        return new MoneyTransactionsExport($filter);
    }

    public function editReceipt(MoneyTransaction $transaction)
    {
        $this->authorize('update', $transaction);

        return view('accounting::transactions.editReceipt', [
            'transaction' => $transaction,
        ]);
    }
    
    public function updateReceipt(Request $request, MoneyTransaction $transaction)
    {
        $this->authorize('update', $transaction);

        if ($transaction->receipt_picture != null) {
            Storage::delete($transaction->receipt_picture);
        }
        $transaction->receipt_picture = $request->file('img')->store('public/accounting/receipts');
        $transaction->save();

        return response(null, 204);
    }

    function deleteReceipt(MoneyTransaction $transaction) 
    {
        $this->authorize('update', $transaction);

        if ($transaction->receipt_picture != null) {
            Storage::delete($transaction->receipt_picture);
        }
        $transaction->receipt_picture = null;
        $transaction->save();

        return response(null, 204);
    }

}
