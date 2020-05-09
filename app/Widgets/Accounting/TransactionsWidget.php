<?php

namespace App\Widgets\Accounting;

use App\Models\Accounting\Wallet;
use App\Widgets\Widget;
use Illuminate\Support\Facades\Gate;

class TransactionsWidget implements Widget
{
    public function authorize(): bool
    {
        return Gate::allows('view-accounting-summary');
    }

    public function view(): string
    {
        return 'accounting.dashboard.widgets.transactions';
    }

    public function args(): array
    {
        return [
            'wallets' => Wallet::orderBy('is_default', 'desc')
                ->orderBy('name')
                ->get(),
            'has_multiple_wallets' => Wallet::count() > 1,
        ];
    }
}
