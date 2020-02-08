<?php

namespace Modules\Collaboration\Providers;

use App\Providers\Traits\RegistersDashboardWidgets;

use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Eloquent\Factory;

class CollaborationServiceProvider extends ServiceProvider
{
    use RegistersDashboardWidgets;

    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

    /**
     * Dashboard widgets
     */
    protected $dashboardWidgets = [
        \Modules\Collaboration\Widgets\KBWidget::class => 6,
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
        $this->loadMigrationsFrom(module_path('Collaboration', 'Database/Migrations'));
        $this->registerDashboardWidgets();
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
        $this->app->register(NavigationServiceProvider::class);
    }

    /**
     * Register config.
     *
     * @return void
     */
    protected function registerConfig()
    {
        $this->publishes([
            module_path('Collaboration', 'Config/config.php') => config_path('collaboration.php'),
        ], 'config');
        $this->mergeConfigFrom(
            module_path('Collaboration', 'Config/config.php'), 'collaboration'
        );
    }

    /**
     * Register views.
     *
     * @return void
     */
    public function registerViews()
    {
        $viewPath = resource_path('views/modules/collaboration');

        $sourcePath = module_path('Collaboration', 'Resources/views');

        $this->publishes([
            $sourcePath => $viewPath
        ],'views');

        $this->loadViewsFrom(array_merge(array_map(function ($path) {
            return $path . '/modules/collaboration';
        }, \Config::get('view.paths')), [$sourcePath]), 'collaboration');
    }

    /**
     * Register translations.
     *
     * @return void
     */
    public function registerTranslations()
    {
        $langPath = resource_path('lang/modules/collaboration');

        if (is_dir($langPath)) {
            $this->loadTranslationsFrom($langPath, 'collaboration');
        } else {
            $this->loadTranslationsFrom(module_path('Collaboration', 'Resources/lang'), 'collaboration');
        }
    }

    /**
     * Register an additional directory of factories.
     *
     * @return void
     */
    public function registerFactories()
    {
        if (! app()->environment('production') && $this->app->runningInConsole()) {
            app(Factory::class)->load(module_path('Collaboration', 'Database/factories'));
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
