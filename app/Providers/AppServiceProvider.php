<?php

namespace App\Providers;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use App\Rules\CountryCode;
use App\Rules\CountryName;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // Database string length
        Schema::defaultStringLength(191);

        // Enforce SSL if running in production
		if (env('APP_ENV') === 'production' || env('APP_ENV') === 'prod') {
			\URL::forceScheme('https');
		}

		// Pagination method for collections
        if (!Collection::hasMacro('paginate')) {
            Collection::macro('paginate',
                function ($perPage = 15, $page = null, $options = []) {
                    $page = $page ?: (Paginator::resolveCurrentPage() ?: 1);
                    return (new LengthAwarePaginator(
                        $this->forPage($page, $perPage), $this->count(), $perPage, $page, $options))
                        ->withPath('');
                });
        }

        // Blade directive for showing a Font Awesome icon
        Blade::directive('icon', function ($name) {
            return '<i class="fa fa-' . $name . '"></i>';
        });

		// Exposes checks agains a specific gate
        Blade::if('allowed', function ($gate) {
            if (is_array($gate)) {
                foreach ($gate as $g) {
                    if (Gate::allows($g)) {
                        return true;
                    }
                }
                return false;
            }
            return Gate::allows($gate);
        });

        // Blade directive to create a link to a telephone number
        Blade::directive('tel', function ($expression) {
            return "<?php echo tel_link($expression); ?>";
        });

        // Blade directive to create a link to a WhatsApp number
        Blade::directive('whatsapp', function ($expression) {
            return "<?php echo whatsapp_link($expression); ?>";
        });

        // Blade directive to create a link to an email address
        Blade::directive('email', function ($expression) {
            return "<?php echo email_link($expression); ?>";
        });

        // Blade directive to create a link to call a skype name
        Blade::directive('skype', function ($expression) {
            return "<?php echo skype_link($expression); ?>";
        });

        Validator::extend('country_code', CountryCode::class);
        Validator::extend('country_name', CountryName::class);
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
