<?php

namespace Modules\Logistics\Providers;

use App\Providers\Traits\RegistersNavigationItems;
use App\Providers\Traits\RegisterContextButtons;

use Illuminate\Support\ServiceProvider;

class NavigationServiceProvider extends ServiceProvider
{
    use RegistersNavigationItems, RegisterContextButtons;

    /**
     * Navigation items
     */
    protected $navigationItems = [
        \Modules\Logistics\Navigation\Drawer\LogisticsNavigationItem::class => 6,
    ];

    protected $contextButtons = [
        'logistics.suppliers.index'  => \Modules\Logistics\Navigation\ContextButtons\SuppliersIndexContextButtons::class,
        'logistics.suppliers.create' => \Modules\Logistics\Navigation\ContextButtons\SuppliersCreateContextButtons::class,
        'logistics.suppliers.edit'   => \Modules\Logistics\Navigation\ContextButtons\SuppliersEditContextButtons::class,
        'logistics.products.index'   => \Modules\Logistics\Navigation\ContextButtons\ProductsIndexContextButtons::class,
        'logistics.products.create'  => \Modules\Logistics\Navigation\ContextButtons\ProductsCreateContextButtons::class,
        'logistics.products.show'    => \Modules\Logistics\Navigation\ContextButtons\ProductsShowContextButtons::class,
        'logistics.products.edit'    => \Modules\Logistics\Navigation\ContextButtons\ProductsEditContextButtons::class,
    ];

    /**
     * Boot the application events.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerNavigationItems();
        $this->registerContextButtons();
    }

}
