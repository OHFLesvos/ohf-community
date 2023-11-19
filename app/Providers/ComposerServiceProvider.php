<?php

namespace App\Providers;

use App\Http\ViewComposers\AppVersionComposer;
use App\Http\ViewComposers\BrandingComposer;
use App\Http\ViewComposers\NavigationComposer;
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
        view()->composer('*', AppVersionComposer::class);
        view()->composer('*', BrandingComposer::class);
        view()->composer(['layouts.app'], NavigationComposer::class);
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
