<?php

namespace App\Navigation\ContextButtons\Accounting;

use App\Models\Accounting\MoneyTransaction;
use App\Navigation\ContextButtons\ContextButtons;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

class WalletChangeContextButtons implements ContextButtons
{
    public function getItems(View $view): array
    {
        return [
            'manage' => [
                'url' => route('accounting.wallets'),
                'caption' => __('app.manage'),
                'icon' => 'cogs',
                'authorized' => Gate::allows('configure-accounting'),
            ],
            'back' => [
                'url' => route('accounting.transactions.index'),
                'caption' => __('app.cancel'),
                'icon' => 'times-circle',
                'authorized' => Auth::user()->can('viewAny', MoneyTransaction::class),
            ],
        ];
    }

}
