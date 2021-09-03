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

    public function render()
    {
        return view('widgets.accounting', [
            'wallets' => Wallet::orderBy('name')
                ->get()
                ->filter(fn (Wallet $wallet) => request()->user()->can('view', $wallet))
                ->map(fn (Wallet $wallet) => [
                    'name' => $wallet->name,
                    'url' => route('accounting.transactions.index', $wallet),
                    'amount_formatted' => $this->formatCurrency($wallet->amount),
                ]),
        ]);
    }
}
