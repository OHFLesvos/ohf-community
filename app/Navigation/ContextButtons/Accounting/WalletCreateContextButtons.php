<?php

namespace App\Navigation\ContextButtons\Accounting;

use App\Models\Accounting\Wallet;
use App\Navigation\ContextButtons\ContextButtons;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class WalletCreateContextButtons implements ContextButtons
{
    public function getItems(View $view): array
    {
        return [
            'back' => [
                'url' => route('accounting.wallets.index'),
                'caption' => __('app.cancel'),
                'icon' => 'times-circle',
                'authorized' => Auth::user()->can('viewAny', Wallet::class),
            ],
        ];
    }

}
