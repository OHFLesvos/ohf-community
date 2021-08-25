<?php

namespace App\Http\Controllers\Accounting\API;

use App\Exports\Accounting\TransactionsExport;
use App\Exports\Accounting\TransactionsMonthsExport;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Export\ExportableActions;
use App\Models\Accounting\Transaction;
use App\Models\Accounting\Wallet;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ExportController extends Controller
{
    use ExportableActions;

    protected function exportAuthorize()
    {
        $this->authorize('viewAny', Transaction::class);
    }

    protected function exportView(): string
    {
        return '';
    }

    protected function exportViewArgs(): array
    {
        return [];
    }

    protected function exportValidateArgs(): array
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

    protected function exportFilename(Request $request): string
    {
        $wallet = Wallet::findOrFail($request->route('wallet'));
        return config('app.name') . ' ' . __('Accounting') . ' [' . $wallet->name . '] (' . Carbon::now()->toDateString() . ')';
    }

    protected function exportExportable(Request $request)
    {
        $wallet = Wallet::findOrFail($request->route('wallet'));
        $advancedFilter = $request->input('advanced_filter', []);
        if ($request->grouping == 'monthly') {
            return new TransactionsMonthsExport($wallet, $advancedFilter);
        }
        return new TransactionsExport($wallet, $advancedFilter);
    }
}
