<?php

namespace Modules\Bank\Navigation\ContextButtons;

use App\Navigation\ContextButtons\ContextButtons;

use App\CouponType;

use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;

class CouponShowContextButtons implements ContextButtons {

    public function getItems(View $view): array
    {
        $coupon = $view->getData()['coupon'];
        return [
            'action' => [
                'url' => route('coupons.edit', $coupon),
                'caption' => __('app.edit'),
                'icon' => 'pencil',
                'icon_floating' => 'pencil',
                'authorized' => Auth::user()->can('update', $coupon)
            ],
            'delete' => [
                'url' => route('coupons.destroy', $coupon),
                'caption' => __('app.delete'),
                'icon' => 'trash',
                'authorized' => Auth::user()->can('delete', $coupon),
                'confirmation' => __('people.confirm_delete_coupon')
            ],
            'back' => [
                'url' => route('coupons.index'),
                'caption' => __('app.close'),
                'icon' => 'times-circle',
                'authorized' => Auth::user()->can('list', CouponType::class)
            ]
        ];
    }

}
