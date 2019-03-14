<?php

namespace App\Providers;

use App\Support\Facades\NavigationItems;

use Illuminate\Support\ServiceProvider;

class NavigationServiceProvider extends ServiceProvider
{
    protected $items = [
        \App\Navigation\HomeNavigationItem::class,
        \App\Navigation\PeopleNavigationItem::class,
        \App\Navigation\BankNavigationItem::class,
        \App\Navigation\HelpersNavigationItem::class,
        \App\Navigation\LogisticsNavigationItem::class,
        \App\Navigation\FundraisingNavigationItem::class,
        \App\Navigation\WikiArticlesItem::class,
        \App\Navigation\AccountingNavigationItem::class,
        \App\Navigation\InventoryStorageNavigationItem::class,
        \App\Navigation\ShopNavigationItem::class,
        \App\Navigation\BarberNavigationItem::class,
        \App\Navigation\LibraryNavigationItem::class,
        \App\Navigation\CalendarNavigationItem::class,
        \App\Navigation\TasksNavigationItem::class,
        \App\Navigation\BadgesNavigationItem::class,
        \App\Navigation\ReportingNavigationItem::class,
        \App\Navigation\UsersNavigationItem::class,
        \App\Navigation\LogViewerNavigationItem::class,
    ];

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        foreach ($this->items as $item)
        {
            NavigationItems::define($item);
        }
    }

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
