<?php

namespace App\View\Widgets;

use App\Models\Accounting\Wallet;
use App\Support\Accounting\FormatsCurrency;
use Illuminate\Support\Facades\Gate;

class AccountingWidget implements Widget
{
    use FormatsCurrency;

    public function authorize(): bool
    {
        return Gate::allows('view-accounting-summary');
    }

    public function key(): string
    {
        return 'accounting';
    }

    public function data(): array
    {
        return [
            'wallets' => Wallet::orderBy('name')
                ->get()
                ->filter(fn (Wallet $wallet) => request()->user()->can('view', $wallet))
                ->map(fn (Wallet $wallet) => [
                    'id' => $wallet->id,
                    'name' => $wallet->name,
                    'amount_formatted' => $this->formatCurrency($wallet->amount),
                ]),
        ];
    }
}
