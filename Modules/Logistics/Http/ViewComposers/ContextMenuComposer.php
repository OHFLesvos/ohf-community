<?php

namespace Modules\Logistics\Http\ViewComposers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Route;
use Illuminate\View\View;

use Modules\Logistics\Entities\Product;
use Modules\Logistics\Entities\Supplier;

class ContextMenuComposer {

	/**
     * Create the composer.
     *
     * @return void
     */
    public function __construct()
    {
    }

    /**
     * Bind data to the view.
     *
     * @param  View  $view
     * @return void
     */
    public function compose(View $view)
    {
        $currentRouteName = Route::currentRouteName();
        $view->with('menu', array_merge($view->getData()['menu'], $this->getMenu($view, $currentRouteName)));
        $view->with('buttons', array_merge($view->getData()['buttons'], $this->getButtons($view, $currentRouteName)));
    }

    /**
     * @param View $view
     * @param string $currentRouteName
     * @return array
     */
    public function getMenu(View $view, string $currentRouteName): array
    {
        switch ($currentRouteName) {

        }
        return [];
    }

    /**
     * @param View $view
     * @param string $currentRouteName
     * @return array
     */
    private function getButtons(View $view, string $currentRouteName): array
    {
        switch ($currentRouteName) {

            //
            // Suppliers
            //
            case 'logistics.suppliers.index':
                return [
                    'action' => [
                        'url' => route('logistics.suppliers.create'),
                        'caption' => __('app.add'),
                        'icon' => 'plus-circle',
                        'icon_floating' => 'plus',
                        'authorized' => Auth::user()->can('create', Supplier::class)
                    ],
                ];
            case 'logistics.suppliers.create':
                return [
                    'back' => [
                        'url' => route('logistics.suppliers.index'),
                        'caption' => __('app.cancel'),
                        'icon' => 'times-circle',
                        'authorized' => Auth::user()->can('create', Supplier::class)
                    ]
                ];
            case 'logistics.suppliers.edit':
                $supplier = $view->getData()['supplier'];
                return [
                    'delete' => [
                        'url' => route('logistics.suppliers.destroy', $supplier),
                        'caption' => __('app.delete'),
                        'icon' => 'trash',
                        'authorized' => Auth::user()->can('delete', $supplier),
                        'confirmation' => __('logistics::suppliers.confirm_delete_supplier')
                    ],
                    'back' => [
                        'url' => route('logistics.suppliers.index'),
                        'caption' => __('app.cancel'),
                        'icon' => 'times-circle',
                        'authorized' => Auth::user()->can('list', Supplier::class)
                    ]
                ];
            
            //
            // Products
            //
            case 'logistics.products.index':
                return [
                    'action' => [
                        'url' => route('logistics.products.create'),
                        'caption' => __('app.add'),
                        'icon' => 'plus-circle',
                        'icon_floating' => 'plus',
                        'authorized' => Auth::user()->can('create', Product::class)
                    ],
                ];
            case 'logistics.products.create':
                return [
                    'back' => [
                        'url' => route('logistics.products.index'),
                        'caption' => __('app.cancel'),
                        'icon' => 'times-circle',
                        'authorized' => Auth::user()->can('create', Product::class)
                    ]
                ];
            case 'logistics.products.show':
                $product = $view->getData()['product'];
                return [
                    'action' => [
                        'url' => route('logistics.products.edit', $product),
                        'caption' => __('app.edit'),
                        'icon' => 'pencil',
                        'icon_floating' => 'pencil',
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
            case 'logistics.products.edit':
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
        return [];
    }
}
