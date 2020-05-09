<?php

namespace App\Navigation\ContextButtons\Accounting;

use App\Models\Accounting\Wallet;
use App\Navigation\ContextButtons\ContextButtons;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class WalletEditContextButtons implements ContextButtons
{
    public function getItems(View $view): array
    {
        $wallet = $view->getData()['wallet'];
        return [
            'delete' => [
                'url' => route('accounting.wallets.destroy', $wallet),
                'caption' => __('app.delete'),
                'icon' => 'trash',
                'authorized' => Auth::user()->can('delete', $wallet),
                'confirmation' => __('accounting.confirm_delete_wallet'),
            ],
            'back' => [
                'url' => route('accounting.wallets.index'),
                'caption' => __('app.cancel'),
                'icon' => 'times-circle',
                'authorized' => Auth::user()->can('viewAny', Wallet::class),
            ],
        ];
    }

}
