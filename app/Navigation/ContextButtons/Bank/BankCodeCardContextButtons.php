<?php

namespace App\Navigation\ContextButtons\Bank;

use App\Models\Bank\CouponType;
use App\Navigation\ContextButtons\ContextButtons;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class BankCodeCardContextButtons implements ContextButtons
{
    public function getItems(View $view): array
    {
        return [
            'back' => [
                'url' => route('bank.coupons.index'),
                'caption' => __('app.close'),
                'icon' => 'times-circle',
                'authorized' => Auth::user()->can('viewAny', CouponType::class),
            ],
        ];
    }
}
