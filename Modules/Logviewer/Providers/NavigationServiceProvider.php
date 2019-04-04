<?php

namespace Modules\Logviewer\Providers;

use App\Providers\RegistersNavigationItems;

use Illuminate\Support\ServiceProvider;

class NavigationServiceProvider extends ServiceProvider
{
    use RegistersNavigationItems;

    /**
     * Navigation items
     */
    protected $navigationItems = [
        \Modules\Logviewer\Navigation\Drawer\LogViewerNavigationItem::class => 16,
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
