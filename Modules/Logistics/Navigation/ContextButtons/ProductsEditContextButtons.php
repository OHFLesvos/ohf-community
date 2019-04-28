<?php

namespace Modules\Logistics\Navigation\ContextButtons;

use App\Navigation\ContextButtons\ContextButtons;

use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;

class ProductsEditContextButtons implements ContextButtons {

    public function getItems(View $view): array
    {
        $product = $view->getData()['product'];
        return [
            'back' => [
                'url' => route('logistics.products.show', $product),
                'caption' => __('app.cancel'),
                'icon' => 'times-circle',
                'authorized' => Auth::user()->can('view', $product)
            ]
        ];
    }

}
