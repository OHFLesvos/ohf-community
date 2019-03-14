<?php

namespace App\Providers;

class NavigationServiceProvider extends BaseNavigationServiceProvider
{
    protected $items = [
        \App\Navigation\HomeNavigationItem::class => 0,
        \App\Navigation\PeopleNavigationItem::class => 1,
        \App\Navigation\BankNavigationItem::class => 2,
        \App\Navigation\HelpersNavigationItem::class => 3,
        \App\Navigation\LogisticsNavigationItem::class => 4,
        \App\Navigation\FundraisingNavigationItem::class => 5,
        \App\Navigation\WikiArticlesItem::class => 6,
        \App\Navigation\InventoryStorageNavigationItem::class => 7,
        \App\Navigation\ShopNavigationItem::class => 8,
        \App\Navigation\BarberNavigationItem::class => 9,
        \App\Navigation\LibraryNavigationItem::class => 10,
        \App\Navigation\CalendarNavigationItem::class => 11,
        \App\Navigation\TasksNavigationItem::class => 12,
        \App\Navigation\BadgesNavigationItem::class => 13,
        \App\Navigation\ReportingNavigationItem::class => 14,
        \App\Navigation\UsersNavigationItem::class => 15,
        \App\Navigation\LogViewerNavigationItem::class => 16,
    ];

}
