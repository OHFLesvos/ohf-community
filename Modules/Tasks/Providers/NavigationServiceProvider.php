<?php

namespace Modules\Tasks\Providers;

use App\Providers\Traits\RegistersNavigationItems;

use Illuminate\Support\ServiceProvider;

class NavigationServiceProvider extends ServiceProvider
{
    use RegistersNavigationItems;

    /**
     * Navigation items
     */
    protected $navigationItems = [
        \Modules\Tasks\Navigation\Drawer\TasksNavigationItem::class => 12,
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
    }

}
