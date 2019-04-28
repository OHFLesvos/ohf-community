@extends('layouts.tabbed_view', [ 
    'nav_elements' => [
        [
            'url' => route('logistics.suppliers.index'),
            'label' => __('logistics::suppliers.suppliers'),
            'icon' => 'location-arrow',
            'active' => function($currentRouteName) {
                return $currentRouteName == 'logistics.suppliers.index';
            },
            'authorized' => Auth::user()->can('list', \Modules\Logistics\Entities\Supplier::class)
        ],
        [
            'url' => route('logistics.products.index'),
            'label' => __('logistics::products.products'),
            'icon' => 'shopping-basket',
            'active' => function($currentRouteName) {
                return $currentRouteName == 'logistics.products.index';
            },
            'authorized' => Auth::user()->can('list', \Modules\Logistics\Entities\Product::class)
        ]
    ] 
])