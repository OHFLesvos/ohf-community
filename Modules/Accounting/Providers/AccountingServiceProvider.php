<?php

namespace Modules\Accounting\Providers;

use App\Providers\RegistersNavigationItems;
use App\Providers\RegistersDashboardWidgets;
use App\Providers\RegisterContextButtons;

use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Eloquent\Factory;

class AccountingServiceProvider extends ServiceProvider
{
    use RegistersNavigationItems, RegistersDashboardWidgets, RegisterContextButtons;

    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

    /**
     * Navigation items
     */
    protected $navigationItems = [
        \Modules\Accounting\Navigation\Drawer\AccountingNavigationItem::class => 7,
    ];

    /**
     * Dashboard widgets
     */
    protected $dashboardWidgets = [
        \Modules\Accounting\Widgets\TransactionsWidget::class => 7,
    ];

    protected $contextButtons = [
        'accounting.transactions.index' => \Modules\Accounting\Navigation\ContextButtons\TransactionIndexContextButtons::class,
        'accounting.transactions.summary' => \Modules\Accounting\Navigation\ContextButtons\TransactionSummaryContextButtons::class,
        'accounting.transactions.create' => \Modules\Accounting\Navigation\ContextButtons\TransactionReturnToIndexContextButtons::class,
        'accounting.transactions.export' => \Modules\Accounting\Navigation\ContextButtons\TransactionReturnToIndexContextButtons::class,
        'accounting.transactions.show' => \Modules\Accounting\Navigation\ContextButtons\TransactionShowContextButtons::class,
        'accounting.transactions.edit' => \Modules\Accounting\Navigation\ContextButtons\TransactionEditContextButtons::class,
        'accounting.transactions.editReceipt' => \Modules\Accounting\Navigation\ContextButtons\TransactionEditReceiptContextButtons::class,
    ];

    /**
     * Boot the application events.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerTranslations();
        $this->registerConfig();
        $this->registerViews();
        $this->registerFactories();
        $this->loadMigrationsFrom(__DIR__ . '/../Database/Migrations');
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->register(RouteServiceProvider::class);
        $this->app->register(AuthServiceProvider::class);
        $this->registerNavigationItems();
        $this->registerDashboardWidgets();
        $this->registerContextButtons();
    }

    /**
     * Register config.
     *
     * @return void
     */
    protected function registerConfig()
    {
        $this->publishes([
            __DIR__.'/../Config/config.php' => config_path('accounting.php'),
        ], 'config');
        $this->mergeConfigFrom(
            __DIR__.'/../Config/config.php', 'accounting'
        );
    }

    /**
     * Register views.
     *
     * @return void
     */
    public function registerViews()
    {
        $viewPath = resource_path('views/modules/accounting');

        $sourcePath = __DIR__.'/../Resources/views';

        $this->publishes([
            $sourcePath => $viewPath
        ],'views');

        $this->loadViewsFrom(array_merge(array_map(function ($path) {
            return $path . '/modules/accounting';
        }, \Config::get('view.paths')), [$sourcePath]), 'accounting');
    }

    /**
     * Register translations.
     *
     * @return void
     */
    public function registerTranslations()
    {
        $langPath = resource_path('lang/modules/accounting');

        if (is_dir($langPath)) {
            $this->loadTranslationsFrom($langPath, 'accounting');
        } else {
            $this->loadTranslationsFrom(__DIR__ .'/../Resources/lang', 'accounting');
        }
    }

    /**
     * Register an additional directory of factories.
     * 
     * @return void
     */
    public function registerFactories()
    {
        if (! app()->environment('production')) {
            app(Factory::class)->load(__DIR__ . '/../Database/factories');
        }
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [];
    }
}
