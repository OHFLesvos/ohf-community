<?php

namespace App\Providers;

use App\Providers\Traits\RegistersNavigationItems;
use App\Providers\Traits\RegisterContextMenus;
use App\Providers\Traits\RegisterContextButtons;

use Illuminate\Support\ServiceProvider;

class NavigationServiceProvider extends ServiceProvider
{
    use RegistersNavigationItems, RegisterContextMenus, RegisterContextButtons;

    protected $navigationItems = [
        \App\Navigation\Drawer\HomeNavigationItem::class => 0,
        \App\Navigation\Drawer\ReportingNavigationItem::class => 14,
    ];

    protected $contextMenus = [
    ];

    protected $contextButtons = [
    ];

    /**
     * Boot the application events.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerNavigationItems();
        $this->registerContextMenus();
        $this->registerContextButtons();
    }
}
