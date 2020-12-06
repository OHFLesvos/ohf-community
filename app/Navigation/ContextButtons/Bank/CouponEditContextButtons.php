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
                'url' => route('coupons.index'),
                'caption' => __('app.cancel'),
                'icon' => 'times-circle',
                'authorized' => Auth::user()->can('viewAny'),
            ],
            'delete' => [
                'url' => route('coupons.destroy', $coupon),
                'caption' => __('app.delete'),
                'icon' => 'trash',
                'authorized' => Auth::user()->can('delete', $coupon),
                'confirmation' => __('coupons.confirm_delete_coupon'),
            ],
        ];
    }

}
