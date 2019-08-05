<?php

namespace Modules\Accounting\Providers;

use Modules\Accounting\Support\Webling\WeblingClient;

use Illuminate\Support\ServiceProvider;
use Illuminate\Contracts\Support\DeferrableProvider;

class WeblingServiceProvider extends ServiceProvider implements DeferrableProvider
{
    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(WeblingClient::class, function ($app) {
            return new WeblingClient(config('accounting.webling.api.url'), config('accounting.webling.api.key'));
        });
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
