<?php

namespace Modules\Logistics\Navigation\ContextButtons;

use App\Navigation\ContextButtons\ContextButtons;

use Modules\Logistics\Entities\Product;

use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;

class ProductsIndexContextButtons implements ContextButtons {

    public function getItems(View $view): array
    {
        return [
            'action' => [
                'url' => route('logistics.products.create'),
                'caption' => __('app.add'),
                'icon' => 'plus-circle',
                'icon_floating' => 'plus',
                'authorized' => Auth::user()->can('create', Product::class)
            ],
        ];
    }

}
