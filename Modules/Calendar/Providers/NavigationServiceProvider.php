<?php

namespace Modules\Calendar\Providers;

use App\Providers\RegistersNavigationItems;
use App\Providers\RegisterContextButtons;

use Illuminate\Support\ServiceProvider;

class NavigationServiceProvider extends ServiceProvider
{
    use RegistersNavigationItems, RegisterContextButtons;

    /**
     * Navigation items
     */
    protected $navigationItems = [
        \Modules\Calendar\Navigation\Drawer\CalendarNavigationItem::class => 11,
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
        $this->registerContextButtons();
    }

}
