<?php

namespace App\Providers;

use App\Providers\Traits\RegistersNavigationItems;
use Illuminate\Support\ServiceProvider;

class NavigationServiceProvider extends ServiceProvider
{
    use RegistersNavigationItems;

    protected $navigationItems = [
        \App\Navigation\Drawer\HomeNavigationItem::class,
        \App\Navigation\Drawer\Visitors\VisitorsNavigationItem::class,
        \App\Navigation\Drawer\CommunityVolunteers\CommunityVolunteersNavigationItem::class,
        \App\Navigation\Drawer\Accounting\AccountingNavigationItem::class,
        \App\Navigation\Drawer\Fundraising\FundraisingNavigationItem::class,
        \App\Navigation\Drawer\Badges\BadgesNavigationItem::class,
        \App\Navigation\Drawer\ReportsNavigationItem::class,
        \App\Navigation\Drawer\UserManagement\UsersNavigationItem::class,
        \App\Navigation\Drawer\Settings\SettingsNavigationItem::class,
    ];

    /**
     * Boot the application events.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerNavigationItems();
    }
}
