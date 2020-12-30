<?php

namespace App\Navigation\ContextButtons\Bank;

use App\Models\Bank\CouponType;
use App\Navigation\ContextButtons\ContextButtons;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

class CouponIndexContextButtons implements ContextButtons
{
    public function getItems(View $view): array
    {
        return [
            'action' => [
                'url' => route('bank.coupons.create'),
                'caption' => __('app.add'),
                'icon' => 'plus-circle',
                'icon_floating' => 'plus',
                'authorized' => Auth::user()->can('create', CouponType::class),
            ],
            'code-cards' => [
                'url' => route('bank.prepareCodeCard'),
                'caption' => __('people.code_cards'),
                'icon' => 'qrcode',
                'authorized' => Gate::allows('do-bank-withdrawals'),
            ],
            'back' => [
                'url' => route('bank.withdrawal.search'),
                'caption' => __('app.close'),
                'icon' => 'times-circle',
                'authorized' => Gate::allows('do-bank-withdrawals'),
            ],
        ];
    }

}
