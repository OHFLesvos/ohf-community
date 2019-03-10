<?php

namespace App\Providers;

class DashboardWidgetsProvider extends BaseDashboardWidgetsProvider
{
    protected $widgets = [
        \App\Widgets\BankWidget::class => 0,
        \App\Widgets\PersonsWidget::class  => 1,
        \App\Widgets\ShopWidget::class => 2,
        \App\Widgets\BarberShopWidget::class => 3,
        \App\Widgets\LibraryWidget::class => 4,
        \App\Widgets\HelpersWidget::class => 5,
        \App\Widgets\WikiArticlesWidget::class => 6,
        \App\Widgets\InventoryWidget::class => 7,
        \App\Widgets\DonorsWidget::class => 8,
        \App\Widgets\ReportingWidget::class => 9,
        \App\Widgets\ToolsWidget::class => 10,
        \App\Widgets\UsersWidget::class => 11,
        \App\Widgets\ChangeLogWidget::class => 12,
    ];

}
