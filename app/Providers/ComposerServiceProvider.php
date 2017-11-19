<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class ComposerServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        view()->composer('*', 'App\Http\ViewComposers\AppVersionComposer');
        view()->composer('layouts.include.side-nav', 'App\Http\ViewComposers\NavigationComposer');
        view()->composer(['layouts.app', 'layouts.include.site-header'], 'App\Http\ViewComposers\ContextMenuComposer');
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
