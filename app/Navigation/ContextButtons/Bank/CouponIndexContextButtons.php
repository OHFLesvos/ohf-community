<?php

namespace App\Navigation\ContextButtons\Bank;

use App\Navigation\ContextButtons\ContextButtons;

use App\Models\Bank\CouponType;

use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class CouponIndexContextButtons implements ContextButtons {

    public function getItems(View $view): array
    {
        return [
            'action' => [
                'url' => route('coupons.create'),
                'caption' => __('app.add'),
                'icon' => 'plus-circle',
                'icon_floating' => 'plus',
                'authorized' => Auth::user()->can('create', CouponType::class)
            ],
            'back' => [
                'url' => route('bank.withdrawal.search'),
                'caption' => __('app.close'),
                'icon' => 'times-circle',
                'authorized' => Gate::allows('do-bank-withdrawals')
            ]
        ];
    }

}
