<?php

namespace Modules\Bank\Navigation\ContextButtons;

use App\Navigation\ContextButtons\ContextButtons;

use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;

class CouponEditContextButtons implements ContextButtons {

    public function getItems(View $view): array
    {
        $coupon = $view->getData()['coupon'];
        return [
            'back' => [
                'url' => route('coupons.show', $coupon),
                'caption' => __('app.cancel'),
                'icon' => 'times-circle',
                'authorized' => Auth::user()->can('view', $coupon)
            ]
        ];
    }

}
