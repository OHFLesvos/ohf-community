<?php

namespace App\View\Widgets;

use App\Models\Accounting\Wallet;
use Illuminate\Support\Facades\Gate;

class AccountingWidget implements Widget
{
    public function authorize(): bool
    {
        return Gate::allows('view-accounting-summary');
    }

    public function view(): string
    {
        return 'widgets.accounting';
    }

    public function args(): array
    {
        return [
            'wallets' => Wallet::orderBy('is_default', 'desc')
                ->orderBy('name')
                ->get()
                ->filter(fn ($wallet) => request()->user()->can('view', $wallet)),
            'has_multiple_wallets' => Wallet::count() > 1,
        ];
    }
}
