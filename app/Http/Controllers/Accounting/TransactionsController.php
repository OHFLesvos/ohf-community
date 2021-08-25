<?php

namespace App\Http\Controllers\Accounting;

use App\Exports\Accounting\TransactionsExport;
use App\Exports\Accounting\TransactionsMonthsExport;
use App\Exports\Accounting\WeblingTransactionsExport;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Export\ExportableActions;
use App\Http\Requests\Accounting\StoreTransaction;
use App\Models\Accounting\Category;
use App\Models\Accounting\Project;
use App\Models\Accounting\Transaction;
use App\Models\Accounting\Supplier;
use App\Models\Accounting\Wallet;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Setting;

class TransactionsController extends Controller
{
    use ExportableActions;

    public function index(Wallet $wallet, Request $request)
    {
        $this->authorize('viewAny', Transaction::class);
        $this->authorize('view', $wallet);

        return view('accounting.transactions.index', [
            'wallet' => $wallet,
        ]);
    }

    private static function addLevelIndentation(array $items): array
    {
        return collect($items)
            ->map(fn($e) => str_repeat("&nbsp;", 4 * $e['indentation']) . $e['name'])
            ->toArray();
    }

    public function create(Wallet $wallet)
    {
        $this->authorize('create', Transaction::class);

        return view('accounting.transactions.create', [
            'attendees' => Transaction::attendees(),
            'categories' => self::addLevelIndentation(Category::getNested(null, 0, true)),
            'secondary_categories' => self::useSecondaryCategories() ? self::getSecondaryCategories() : null,
            'fixed_secondary_categories' => Setting::has('accounting.transactions.secondary_categories'),
            'projects' => self::addLevelIndentation(Project::getNested(null, 0, true)),
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

        return view('accounting.transactions.show', [
            'transaction' => $transaction,
        ]);
    }

    public function snippet(Transaction $transaction)
    {
        $this->authorize('view', $transaction);

        return view('accounting.transactions.snippet', [
            'transaction' => $transaction,
        ]);
    }

    public function edit(Transaction $transaction)
    {
        $this->authorize('update', $transaction);

        return view('accounting.transactions.edit', [
            'transaction' => $transaction,
        ]);
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

    private static function showIntermediateBalances(): bool
    {
        return Setting::get('accounting.transactions.show_intermediate_balances') ?? false;
    }
}
