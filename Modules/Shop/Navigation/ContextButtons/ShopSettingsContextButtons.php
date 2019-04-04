<?php

namespace Modules\Shop\Navigation\ContextButtons;

use App\Navigation\ContextButtons\ContextButtons;

use Illuminate\View\View;
use Illuminate\Support\Facades\Gate;

class ShopSettingsContextButtons implements ContextButtons {

    public function getItems(View $view): array
    {
        return [
            'back' => [
                'url' => route('shop.index'),
                'caption' => __('app.cancel'),
                'icon' => 'times-circle',
                'authorized' => Gate::allows('validate-shop-coupons')
            ]
        ];
    }

}
