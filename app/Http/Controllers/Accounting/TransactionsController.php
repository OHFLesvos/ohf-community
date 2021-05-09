<?php

namespace App\Http\Controllers\Accounting;

use App\Exports\Accounting\TransactionsExport;
use App\Exports\Accounting\TransactionsMonthsExport;
use App\Exports\Accounting\WeblingTransactionsExport;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Export\ExportableActions;
use App\Http\Requests\Accounting\StoreTransaction;
use App\Models\Accounting\Transaction;
use App\Models\Accounting\Supplier;
use App\Models\Accounting\Wallet;
use App\Support\Accounting\TaxonomyRepository;
use App\Support\Accounting\Webling\Entities\Entrygroup;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Setting;

class TransactionsController extends Controller
{
    use ExportableActions;

    public function index(Wallet $wallet, Request $request, TaxonomyRepository $taxonomies)
    {
        $this->authorize('viewAny', Transaction::class);
        $this->authorize('view', $wallet);

        $request->validate([
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
            'sortColumn' => 'nullable|in:date,created_at,category,secondary_category,project,location,cost_center,attendee,receipt_no',
            'sortOrder' => 'nullable|in:asc,desc',
        ]);

        $sortColumns = [
            'date' => __('Date'),
            'category' => __('Category'),
            'secondary_category' => __('Secondary Category'),
            'project' => __('Project'),
            'location' => __('Location'),
            'cost_center' => __('Cost Center'),
            'attendee' => __('Attendee'),
            'receipt_no' => __('Receipt'),
            'created_at' => __('Registered'),
        ];
        $sortColumn = session('accounting.sortColumn', self::showIntermediateBalances() ? 'receipt_no' : 'created_at');
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
        foreach (config('accounting.filter_columns') as $col) {
            if (! empty($request->filter[$col])) {
                $filter[$col] = $request->filter[$col];
            } elseif (isset($request->filter)) {
                unset($filter[$col]);
            }
        }
        if (! empty($request->filter['date_start'])) {
            $filter['date_start'] = $request->filter['date_start'];
        } elseif (isset($request->filter)) {
            unset($filter['date_start']);
        }
        if (! empty($request->filter['date_end'])) {
            $filter['date_end'] = $request->filter['date_end'];
        } elseif (isset($request->filter)) {
            unset($filter['date_end']);
        }
        session(['accounting.filter' => $filter]);

        $query = self::createIndexQuery($wallet, $filter, $sortColumn, $sortOrder);

        // Get results
        $transactions = $query->paginate(250);

        // Single receipt no. query
        if ($transactions->count() == 1 && ! empty($filter['receipt_no'])) {
            session(['accounting.filter' => []]);
            return redirect()->route('accounting.transactions.show', $transactions->first());
        }

        $hasSuppliers = Supplier::count() > 0;

        return view('accounting.transactions.index', [
            'transactions' => $transactions,
            'filter' => $filter,
            'sortColumns' => $sortColumns,
            'sortColumn' => $sortColumn,
            'sortOrder' => $sortOrder,
            'attendees' => Transaction::attendees(),
            'categories' => $taxonomies->getNestedCategories(),
            'secondary_categories' => self::useSecondaryCategories() ? self::getSecondaryCategories(true) : null,
            'fixed_secondary_categories' => Setting::has('accounting.transactions.secondary_categories'),
            'projects' => $taxonomies->getNestedProjects(),
            'locations' => self::useLocations() ? self::getLocations(true) : null,
            'fixed_locations' => Setting::has('accounting.transactions.locations'),
            'cost_centers' => self::useCostCenters() ? self::getCostCenters(true) : null,
            'fixed_cost_centers' => Setting::has('accounting.transactions.cost_centers'),
            'wallet' => $wallet,
            'has_multiple_wallets' => Wallet::count() > 1,
            'intermediate_balances' => ($sortColumn == 'receipt_no' && self::showIntermediateBalances()) ? self::getIntermediateBalances($wallet) : null,
            'has_suppliers' => $hasSuppliers,
            'suppliers' => $hasSuppliers ? Supplier::query()
                ->has('transactions')
                ->select('id', 'name', 'category')
                ->orderBy('name')
                ->get() : [],
        ]);
    }

    private static function createIndexQuery(Wallet $wallet, array $filter, string $sortColumn, string $sortOrder)
    {
        return Transaction::query()
            ->forWallet($wallet)
            ->forFilter($filter)
            ->orderBy($sortColumn, $sortOrder)
            ->orderBy('created_at', 'DESC');
    }

    public function create(Wallet $wallet, TaxonomyRepository $taxonomies)
    {
        $this->authorize('create', Transaction::class);

        return view('accounting.transactions.create', [
            'attendees' => Transaction::attendees(),
            'categories' => $taxonomies->getNestedCategories(),
            'secondary_categories' => self::useSecondaryCategories() ? self::getSecondaryCategories() : null,
            'fixed_secondary_categories' => Setting::has('accounting.transactions.secondary_categories'),
            'projects' => $taxonomies->getNestedProjects(),
            'locations' => self::useLocations() ? self::getLocations() : null,
            'fixed_locations' => Setting::has('accounting.transactions.locations'),
            'cost_centers' => self::useCostCenters() ? self::getCostCenters() : null,
            'fixed_cost_centers' => Setting::has('accounting.transactions.cost_centers'),
            'suppliers' => Supplier::select('id', 'name', 'category')->orderBy('name')->get(),
            'wallet' => $wallet,
        ]);
    }

    private static function useSecondaryCategories(): bool
    {
        return Setting::get('accounting.transactions.use_secondary_categories') ?? false;
    }

    private static function getSecondaryCategories(?bool $onlyExisting = false): array
    {
        if (! $onlyExisting && Setting::has('accounting.transactions.secondary_categories')) {
            return collect(Setting::get('accounting.transactions.secondary_categories'))
                ->sort()
                ->toArray();
        }
        return Transaction::secondaryCategories();
    }

    private static function useLocations(): bool
    {
        return Setting::get('accounting.transactions.use_locations') ?? false;
    }

    private static function getLocations(?bool $onlyExisting = false): array
    {
        if (! $onlyExisting && Setting::has('accounting.transactions.locations')) {
            return collect(Setting::get('accounting.transactions.locations'))
                ->sort()
                ->toArray();
        }
        return Transaction::locations();
    }

    private static function useCostCenters(): bool
    {
        return Setting::get('accounting.transactions.use_cost_centers') ?? false;
    }

    private static function getCostCenters(?bool $onlyExisting = false): array
    {
        if (! $onlyExisting && Setting::has('accounting.transactions.cost_centers')) {
            return collect(Setting::get('accounting.transactions.cost_centers'))
                ->sort()
                ->toArray();
        }
        return Transaction::costCenters();
    }

    public function store(Wallet $wallet, StoreTransaction $request)
    {
        $this->authorize('create', Transaction::class);
        $this->authorize('view', $wallet);

        $transaction = new Transaction();
        $transaction->date = $request->date;
        $transaction->receipt_no = $request->receipt_no;
        $transaction->type = $request->type;
        $transaction->amount = $request->amount;
        $transaction->fees = $request->fees;
        $transaction->attendee = $request->attendee;
        $transaction->category()->associate($request->category_id);
        if (self::useSecondaryCategories()) {
            $transaction->secondary_category = $request->secondary_category;
        }
        $transaction->project()->associate($request->project_id);
        if (self::useLocations()) {
            $transaction->location = $request->location;
        }
        if (self::useCostCenters()) {
            $transaction->cost_center = $request->cost_center;
        }
        $transaction->description = $request->description;
        $transaction->remarks = $request->remarks;

        $transaction->supplier()->associate($request->input('supplier_id'));

        $transaction->wallet()->associate($wallet);

        if (isset($request->receipt_picture) && is_array($request->receipt_picture)) {
            for ($i = 0; $i < count($request->receipt_picture); $i++) {
                $transaction->addReceiptPicture($request->file('receipt_picture')[$i]);
            }
        }

        $transaction->save();

        return redirect()
            ->route($request->submit == 'save_and_continue' ? 'accounting.transactions.create' : 'accounting.transactions.index', $transaction->wallet)
            ->with('info', __('Transaction registered.'));
    }

    public function show(Transaction $transaction)
    {
        $this->authorize('view', $transaction);

        $sortColumn = session('accounting.sortColumn', 'created_at');
        $sortOrder = session('accounting.sortOrder', 'desc');
        $filter = session('accounting.filter', []);
        $query = self::createIndexQuery($transaction->wallet, $filter, $sortColumn, $sortOrder);
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

        return view('accounting.transactions.show', [
            'transaction' => $transaction,
            'prev_id' => $prev_id,
            'next_id' => $next_id,
        ]);
    }

    public function snippet(Transaction $transaction)
    {
        $this->authorize('view', $transaction);

        return view('accounting.transactions.snippet', [
            'transaction' => $transaction,
        ]);
    }

    public function edit(Transaction $transaction, TaxonomyRepository $taxonomies)
    {
        $this->authorize('update', $transaction);

        return view('accounting.transactions.edit', [
            'transaction' => $transaction,
            'attendees' => Transaction::attendees(),
            'categories' => $taxonomies->getNestedCategories(),
            'secondary_categories' => self::useSecondaryCategories() ? self::getSecondaryCategories() : null,
            'fixed_secondary_categories' => Setting::has('accounting.transactions.secondary_categories'),
            'projects' => $taxonomies->getNestedProjects(),
            'locations' => self::useLocations() ? self::getLocations() : null,
            'fixed_locations' => Setting::has('accounting.transactions.locations'),
            'cost_centers' => self::useCostCenters() ? self::getCostCenters() : null,
            'fixed_cost_centers' => Setting::has('accounting.transactions.cost_centers'),
            'suppliers' => Supplier::select('id', 'name', 'category')->orderBy('name')->get(),
        ]);
    }

    public function update(StoreTransaction $request, Transaction $transaction)
    {
        $this->authorize('update', $transaction);

        $transaction->date = $request->date;
        $transaction->receipt_no = $request->receipt_no;
        $transaction->type = $request->type;
        $transaction->amount = $request->amount;
        $transaction->fees = $request->fees;
        $transaction->attendee = $request->attendee;
        $transaction->category()->associate($request->category_id);
        if (self::useSecondaryCategories()) {
            $transaction->secondary_category = $request->secondary_category;
        }
        $transaction->project()->associate($request->project_id);
        if (self::useLocations()) {
            $transaction->location = $request->location;
        }
        if (self::useCostCenters()) {
            $transaction->cost_center = $request->cost_center;
        }
        $transaction->description = $request->description;
        $transaction->remarks = $request->remarks;

        $transaction->supplier()->associate($request->input('supplier_id'));

        if (isset($request->remove_receipt_picture) && is_array($request->remove_receipt_picture)) {
            foreach ($request->remove_receipt_picture as $picture) {
                $transaction->deleteReceiptPicture($picture);
            }
        } elseif (isset($request->receipt_picture) && is_array($request->receipt_picture)) {
            for ($i = 0; $i < count($request->receipt_picture); $i++) {
                $transaction->addReceiptPicture($request->file('receipt_picture')[$i]);
            }
        }

        $transaction->save();

        return redirect()
            ->route('accounting.transactions.index', $transaction->wallet)
            ->with('info', __('Transaction updated.'));
    }

    public function destroy(Transaction $transaction)
    {
        $this->authorize('delete', $transaction);

        $wallet = $transaction->wallet;

        $transaction->delete();

        return redirect()
            ->route('accounting.transactions.index', $wallet)
            ->with('info', __('Transaction deleted.'));
    }

    protected function exportAuthorize()
    {
        $this->authorize('viewAny', Transaction::class);
    }

    protected function exportView(): string
    {
        return 'accounting.transactions.export';
    }

    protected function exportViewArgs(): array
    {
        $filter = session('accounting.filter', []);
        return [
            'wallet' => Wallet::findOrFail(request()->route('wallet')),
            'columnsSelection' => [
                'all' => __('All'),
                'webling' => __('Selection for Webling Accounting'),
            ],
            'columns' => 'all',
            'groupings' => [
                'none' => __('None'),
                'monthly' => __('Monthly'),
            ],
            'grouping' => 'none',
            'selections' => ! empty($filter) ? [
                'all' => __('All records'),
                'filtered' => __('Selected records according to current filter'),
            ] : null,
            'selection' => 'all',
        ];
    }

    protected function exportValidateArgs(): array
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

    protected function exportFilename(Request $request): string
    {
        $wallet = Wallet::findOrFail($request->route('wallet'));
        return config('app.name') . ' ' . __('Accounting') . ' [' . $wallet->name . '] (' . Carbon::now()->toDateString() . ')';
    }

    protected function exportExportable(Request $request)
    {
        $wallet = Wallet::findOrFail($request->route('wallet'));
        $filter = $request->selection == 'filtered' ? session('accounting.filter', []) : [];
        if ($request->grouping == 'monthly') {
            return new TransactionsMonthsExport($wallet, $filter);
        }
        if ($request->columns == 'webling') {
            return new WeblingTransactionsExport($wallet, $filter);
        }
        return new TransactionsExport($wallet, $filter);
    }

    public function undoBooking(Transaction $transaction)
    {
        $this->authorize('undoBooking', $transaction);

        if ($transaction->external_id != null && Entrygroup::find($transaction->external_id) != null) {
            return redirect()
                ->route('accounting.transactions.show', $transaction)
                ->with('error', __('Transaction not updated; the external record still exists and has to be deleted beforehand.'));
        }

        $transaction->booked = false;
        $transaction->external_id = null;
        $transaction->save();

        return redirect()
            ->route('accounting.transactions.show', $transaction)
            ->with('info', __('Transaction updated.'));
    }

    private static function showIntermediateBalances(): bool
    {
        return Setting::get('accounting.transactions.show_intermediate_balances') ?? false;
    }

    private static function getIntermediateBalances(Wallet $wallet): array
    {
        $transactions = Transaction::query()
            ->forWallet($wallet)
            ->orderBy('receipt_no', 'ASC')
            ->get();

        $intermediate_balances = [];
        $intermediate_balance = 0;
        foreach ($transactions as $transaction) {
            if ($transaction->type == 'income') {
                $intermediate_balance += $transaction->amount;
            } else {
                $intermediate_balance -= $transaction->amount;
            }
            $intermediate_balances[$transaction->id] = $intermediate_balance;
        }

        return $intermediate_balances;
    }
}
