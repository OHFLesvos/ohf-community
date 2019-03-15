<?php

namespace App\Navigation\ContextButtons;

use App\CouponType;

use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;

class CouponCreateContextButtons implements ContextButtons {

    public function getItems(View $view): array
    {
        return [
            'back' => [
                'url' => route('coupons.index'),
                'caption' => __('app.cancel'),
                'icon' => 'times-circle',
                'authorized' => Auth::user()->can('list', CouponType::class)
            ]
        ];
    }

}
