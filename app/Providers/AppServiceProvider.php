<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\View;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Schema::defaultStringLength(191);
		if (env('APP_ENV') === 'production') {
			\URL::forceScheme('https');
		}
		
		// Register directive to output environment name
		Blade::directive('environment', function ($expression) {
            return app()->environment();
        });
		
		View::share( 'app_version', \App\Util\ApplicationVersion::get() );
		
		View::share( 'num_open_tasks', \App\Task::open()->count() );
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
