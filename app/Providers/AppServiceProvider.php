<?php

namespace App\Providers;

use App\Mixins\BuilderMixin;
use App\Providers\Traits\RegistersDashboardWidgets;
use App\Rules\CountryCode;
use App\Rules\CountryName;
use App\Rules\LanguageCode;
use App\Rules\LanguageName;
use App\Rules\Library\Isbn;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    use RegistersDashboardWidgets;

    protected $dashboardWidgets = [
        \App\Widgets\ReportingWidget::class               => 9,
        \App\Widgets\UserManagement\UsersWidget::class    => 11,
        \App\Widgets\Fundraising\FundraisingWidget::class => 8,
        \App\Widgets\Accounting\TransactionsWidget::class => 7,
        \App\Widgets\Collaboration\KBWidget::class        => 6,
        \App\Widgets\People\PersonsWidget::class          => 1,
        \App\Widgets\Bank\BankWidget::class               => 0,
        \App\Widgets\Visitors\VisitorsWidget::class       => 0,
        \App\Widgets\CommunityVolunteers\CommunityVolunteersWidget::class => 5,
        \App\Widgets\Library\LibraryWidget::class         => 4,
        \App\Widgets\Shop\ShopWidget::class               => 2,
    ];

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

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
        if (config('app.env') === 'production' || config('app.env') === 'prod') {
            \URL::forceScheme('https');
        }

        Paginator::useBootstrap();

        // Pagination method for collections
        if (! Collection::hasMacro('paginate')) {
            Collection::macro('paginate',
                function ($perPage = 15, $page = null, $options = []) {
                    $page = $page ?: (Paginator::resolveCurrentPage() ?: 1);
                    $forPage = $this->forPage($page, $perPage);
                    $count = $this->count();
                    $paginator = new LengthAwarePaginator($forPage, $count, $perPage, $page, $options);
                    return $paginator->withPath('');
                });
        }

        // Blade directive for showing a Font Awesome icon
        Blade::directive('icon', fn ($name) => '<i class="fa fa-' . $name . '"></i>');

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
        Blade::directive('tel', fn ($expression) => "<?php echo tel_link(${expression}); ?>");

        // Blade directive to create a link to a WhatsApp number
        Blade::directive('whatsapp', fn ($expression) => "<?php echo whatsapp_link(${expression}); ?>");

        // Blade directive to create a link to an email address
        Blade::directive('email', fn ($expression) => "<?php echo email_link(${expression}); ?>");

        // Blade directive to create a link to call a skype name
        Blade::directive('skype', fn ($expression) => "<?php echo skype_link(${expression}); ?>");

        // UTF-8 support for Carbon time
        Carbon::setUtf8(true);

        $this->registerQueryBuilderMacros();
        $this->registerRules();
        $this->registerDashboardWidgets();
    }

    private function registerQueryBuilderMacros()
    {
        Builder::mixin(new BuilderMixin());
    }

    private function registerRules()
    {
        Validator::extend('country_code', CountryCode::class);
        Validator::extend('country_name', CountryName::class);
        Validator::extend('language_code', LanguageCode::class);
        Validator::extend('language_name', LanguageName::class);
        Validator::extend('isbn', Isbn::class);
    }
}
