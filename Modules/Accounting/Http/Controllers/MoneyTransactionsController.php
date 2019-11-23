<?php

namespace Modules\Accounting\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Export\ExportableActions;

use Modules\Accounting\Http\Requests\StoreTransaction;
use Modules\Accounting\Entities\MoneyTransaction;
use Modules\Accounting\Exports\MoneyTransactionsExport;
use Modules\Accounting\Exports\WeblingMoneyTransactionsExport;
use Modules\Accounting\Exports\MoneyTransactionsMonthsExport;
use Modules\Accounting\Support\Webling\Entities\Entrygroup;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Config;
use Illuminate\Validation\Rule;

use Carbon\Carbon;

use Setting;

class MoneyTransactionsController extends Controller
{
    use ExportableActions;

    const CATEGORIES_SETTING_KEY = 'accounting.transactions.categories';
    const PROJECTS_SETTING_KEY = 'accounting.transactions.projects';

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
            'categories' => self::getCategories(true),
            'fixed_categories' => Setting::has(self::CATEGORIES_SETTING_KEY),
            'projects' => self::getProjects(true),
            'fixed_projects' => Setting::has(self::PROJECTS_SETTING_KEY),
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
                        $query->orWhereNull('receipt_pictures');
                        $query->orWhere('receipt_pictures', '[]');
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
            'fixed_categories' => Setting::has(self::CATEGORIES_SETTING_KEY),
            'projects' => self::getProjects(),
            'fixed_projects' => Setting::has(self::PROJECTS_SETTING_KEY),
            'newReceiptNo' => MoneyTransaction::getNextFreeReceiptNo(),
        ]);
    }

    private static function getBeneficiaries() {
        return MoneyTransaction::select('beneficiary')
            ->groupBy('beneficiary')
            ->orderBy('beneficiary')
            ->get()
            ->pluck('beneficiary')
            ->unique()
            ->toArray();
    }

    private static function getCategories($onlyExisting = false) {
        if (!$onlyExisting && Setting::has(self::CATEGORIES_SETTING_KEY)) {
            return collect(Setting::get(self::CATEGORIES_SETTING_KEY))
                ->mapWithKeys(function($e){
                    return [ $e => $e ];
                })
                ->sort()
                ->toArray();
        }
        return MoneyTransaction::select('category')
            ->groupBy('category')
            ->orderBy('category')
            ->get()
            ->pluck('category', 'category')
            ->unique()
            ->toArray();
    }

    private static function getProjects($onlyExisting = false) {
        if (!$onlyExisting && Setting::has(self::PROJECTS_SETTING_KEY)) {
            return collect(Setting::get(self::PROJECTS_SETTING_KEY))
                ->mapWithKeys(function($e){
                    return [ $e => $e ];
                })
                ->sort()
                ->toArray();
        }        
        return MoneyTransaction::select('project')
            ->where('project', '!=', null)
            ->groupBy('project')
            ->orderBy('project')
            ->get()
            ->pluck('project', 'project')
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
            $transaction->addReceiptPicture($request->file('receipt_picture'));
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
            'fixed_categories' => Setting::has(self::CATEGORIES_SETTING_KEY),
            'projects' => self::getProjects(),
            'fixed_projects' => Setting::has(self::PROJECTS_SETTING_KEY),
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
            $transaction->deleteReceiptPictures();
        }
        else if (isset($request->receipt_picture)) {
            $transaction->deleteReceiptPictures(); // TODO no need to clear pictures for multi picture support
            $transaction->addReceiptPicture($request->file('receipt_picture'));
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

        $validatedData = $request->validate([
            'month' => 'nullable|integer|min:1|max:12',
            'year' => 'nullable|integer|min:2000|max:' . Carbon::today()->year,
        ]);

        if ($request->filled('year') && $request->filled('month')) {
            $year = $request->year;
            $month = $request->month;
        } else if ($request->filled('year')) {
            $year = $request->year;
            $month = null;
        } else if ($request->has('year')) {
            $year = null;
            $month = null;
        } else if ($request->session()->has('accounting.summary_range.year') && $request->session()->has('accounting.summary_range.month')) {
            $year = $request->session()->get('accounting.summary_range.year');
            $month = $request->session()->get('accounting.summary_range.month');
        } else if ($request->session()->has('accounting.summary_range.year')) {
            $year = $request->session()->get('accounting.summary_range.year');
            $month = null;
        } else if ($request->session()->exists('accounting.summary_range.year')) {
            $year = null;
            $month = null;
        } else {
            $year = $currentMonth->year;
            $month = $currentMonth->month;
        }
        session([
            'accounting.summary_range.year' => $year,
            'accounting.summary_range.month' => $month,
        ]);

        if ($year != null && $month != null) {
            $dateFrom = (new Carbon($year.'-'.$month.'-01'))->startOfMonth();
            $dateTo = (clone $dateFrom)->endOfMonth();
            $heading = $dateFrom->formatLocalized('%B %Y');
            $currentRange = $dateFrom->format('Y-m');
        } else if ($year != null) {
            $dateFrom = (new Carbon($year.'-01-01'))->startOfYear();
            $dateTo = (clone $dateFrom)->endOfYear();
            $heading = $year;
            $currentRange = $year;
        } else {
            $dateFrom = null;
            $dateTo = null;
            $heading = __('app.all_time');
            $currentRange = null;
        }

        // TODO: Probably define on more general location
        setlocale(LC_TIME, \App::getLocale());

        $revenueByCategory = MoneyTransaction::revenueByField('category', $dateFrom, $dateTo);
        $revenueByProject = MoneyTransaction::revenueByField('project', $dateFrom, $dateTo);
        $wallet = MoneyTransaction::currentWallet($dateTo);

        $spending = MoneyTransaction::totalSpending($dateFrom, $dateTo);
        $income = MoneyTransaction::totalIncome($dateFrom, $dateTo);

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

        $years = MoneyTransaction
            ::select(DB::raw('YEAR(date) as year'))
            ->groupBy(DB::raw('YEAR(date)'))
            ->orderBy('year', 'desc')
            ->get()
            ->mapWithKeys(function($e){
                return [ $e->year => $e->year ];
            })            
            ->prepend($currentMonth->format('Y'), $currentMonth->format('Y'))
            ->toArray();

        return view('accounting::transactions.summary', [
            'heading' => $heading,
            'currentRange' => $currentRange,
            'months' => $months,
            'years' => $years,
            'revenueByCategory' => $revenueByCategory,
            'revenueByProject' => $revenueByProject,
            'wallet' => $wallet,
            'spending' => $spending,
            'income' => $income,
            'filterDateStart' => optional($dateFrom)->toDateString(),
            'filterDateEnd' => optional($dateTo)->toDateString(),
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
            'columnsSelection' => [
                'all' => __('app.all'),
                'webling' => __('accounting::accounting.selection_for_webling'),
            ],
            'columns' => 'all',
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
            'columns' => [
                'required', 
                Rule::in(['all', 'webling']),
            ],
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
        if ($request->columns == 'webling') {
            return new WeblingMoneyTransactionsExport($filter);
        }
        return new MoneyTransactionsExport($filter);
    }
    
    public function updateReceipt(Request $request, MoneyTransaction $transaction)
    {
        $this->authorize('update', $transaction);

        $transaction->deleteReceiptPictures(); // TODO no need to clear pictures for multi picture support
        $transaction->addReceiptPicture($request->file('img'));
        $transaction->save();

        return response(null, 204);
    }

    public function undoBooking(Request $request, MoneyTransaction $transaction)
    {
        $this->authorize('undoBooking', $transaction);
        
        if ($transaction->external_id != null && Entrygroup::find($transaction->external_id) != null) {
            return redirect()
                ->route('accounting.transactions.show', $transaction)
                ->with('error', __('accounting::accounting.transaction_not_updated_external_record_still_exists'));
        }

        $transaction->booked = false;
        $transaction->external_id = null;
        $transaction->save();

        return redirect()
            ->route('accounting.transactions.show', $transaction)
            ->with('info', __('accounting::accounting.transactions_updated'));
    }
}
