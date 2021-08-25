<?php

namespace App\Http\Controllers\Accounting;

use App\Exports\Accounting\TransactionsExport;
use App\Exports\Accounting\TransactionsMonthsExport;
use App\Exports\Accounting\WeblingTransactionsExport;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Export\ExportableActions;
use App\Models\Accounting\Transaction;
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

    public function create(Wallet $wallet)
    {
        $this->authorize('create', Transaction::class);

        return view('accounting.transactions.create', [
            'wallet' => $wallet,
        ]);
    }

    public function show(Transaction $transaction)
    {
        $this->authorize('view', $transaction);

        return view('accounting.transactions.show');
    }

    public function edit(Transaction $transaction)
    {
        $this->authorize('update', $transaction);

        return view('accounting.transactions.edit');
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
