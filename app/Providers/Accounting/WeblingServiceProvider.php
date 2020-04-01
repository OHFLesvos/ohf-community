<?php

namespace App\Providers\Accounting;

use App\Support\Accounting\Webling\WeblingClient;
use Illuminate\Contracts\Support\DeferrableProvider;
use Illuminate\Support\ServiceProvider;

class WeblingServiceProvider extends ServiceProvider implements DeferrableProvider
{
    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(WeblingClient::class, fn ($app) => new WeblingClient(
            config('accounting.webling.api.url'),
            config('accounting.webling.api.key')
        ));
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [WeblingClient::class];
    }
}
