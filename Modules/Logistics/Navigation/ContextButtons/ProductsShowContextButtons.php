<?php

namespace Modules\Logistics\Navigation\ContextButtons;

use App\Navigation\ContextButtons\ContextButtons;

use Modules\Logistics\Entities\Product;

use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;

class ProductsShowContextButtons implements ContextButtons {

    public function getItems(View $view): array
    {
        $product = $view->getData()['product'];
        return [
            'action' => [
                'url' => route('logistics.products.edit', $product),
                'caption' => __('app.edit'),
                'icon' => 'edit',
                'icon_floating' => 'pencil-alt',
                'authorized' => Auth::user()->can('update', $product)
            ],
            'delete' => [
                'url' => route('logistics.products.destroy', $product),
                'caption' => __('app.delete'),
                'icon' => 'trash',
                'authorized' => Auth::user()->can('delete', $product),
                'confirmation' => __('logistics::products.confirm_delete_product')
            ],
            'back' => [
                'url' => route('logistics.products.index'),
                'caption' => __('app.close'),
                'icon' => 'times-circle',
                'authorized' => Auth::user()->can('list', Product::class)
            ]
        ];
    }

}
