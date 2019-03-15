<?php

namespace App\Providers;

use App\Rules\CountryCode;
use App\Rules\CountryName;
use App\Rules\Isbn;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;

class AppServiceProvider extends ServiceProvider
{
    use RegistersNavigationItems, RegistersDashboardWidgets, RegisterContextMenus;

    protected $navigationItems = [
        \App\Navigation\HomeNavigationItem::class => 0,
        \App\Navigation\PeopleNavigationItem::class => 1,
        \App\Navigation\BankNavigationItem::class => 2,
        \App\Navigation\HelpersNavigationItem::class => 3,
        \App\Navigation\LogisticsNavigationItem::class => 4,
        \App\Navigation\FundraisingNavigationItem::class => 5,
        \App\Navigation\WikiArticlesItem::class => 6,
        \App\Navigation\InventoryStorageNavigationItem::class => 7,
        \App\Navigation\ShopNavigationItem::class => 8,
        \App\Navigation\BarberNavigationItem::class => 9,
        \App\Navigation\LibraryNavigationItem::class => 10,
        \App\Navigation\CalendarNavigationItem::class => 11,
        \App\Navigation\TasksNavigationItem::class => 12,
        \App\Navigation\BadgesNavigationItem::class => 13,
        \App\Navigation\ReportingNavigationItem::class => 14,
        \App\Navigation\UsersNavigationItem::class => 15,
        \App\Navigation\LogViewerNavigationItem::class => 16,
    ];

    protected $contextMenus = [
        \App\Navigation\ContextMenu\PeopleContextMenu::class,
        \App\Navigation\ContextMenu\BankWithdrawalContextMenu::class,
        \App\Navigation\ContextMenu\HelpersContextMenu::class,
    ];

    protected $dashboardWidgets = [
        \App\Widgets\BankWidget::class => 0,
        \App\Widgets\PersonsWidget::class  => 1,
        \App\Widgets\ShopWidget::class => 2,
        \App\Widgets\BarberShopWidget::class => 3,
        \App\Widgets\LibraryWidget::class => 4,
        \App\Widgets\HelpersWidget::class => 5,
        \App\Widgets\WikiArticlesWidget::class => 6,
        \App\Widgets\InventoryWidget::class => 7,
        \App\Widgets\DonorsWidget::class => 8,
        \App\Widgets\ReportingWidget::class => 9,
        \App\Widgets\ToolsWidget::class => 10,
        \App\Widgets\UsersWidget::class => 11,
        \App\Widgets\ChangeLogWidget::class => 12,
    ];

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
        Validator::extend('isbn', Isbn::class);

        $this->registerNavigationItems();
        $this->registerDashboardWidgets();
        $this->registerContextMenus();
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
