<?php

namespace App\Navigation\ContextButtons\Bank;

use App\Navigation\ContextButtons\ContextButtons;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class CouponEditContextButtons implements ContextButtons
{
    public function getItems(View $view): array
    {
        $coupon = $view->getData()['coupon'];
        return [
            'back' => [
                'url' => route('coupons.show', $coupon),
                'caption' => __('app.cancel'),
                'icon' => 'times-circle',
                'authorized' => Auth::user()->can('view', $coupon),
            ],
        ];
    }

}
