<?php

namespace App\Providers;

use App\Support\Facades\NavigationItems;

use Illuminate\Support\ServiceProvider;

abstract class BaseNavigationServiceProvider extends ServiceProvider
{
    protected $items = [];

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        foreach ($this->items as $itemClass => $position)
        {
            NavigationItems::define($itemClass, $position);
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
