<?php

namespace App\Providers;

use App\Support\Facades\DashboardWidgets;

use Illuminate\Support\ServiceProvider;

class BaseDashboardWidgetsProvider extends ServiceProvider
{
    protected $widgets = [ ];

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerWidgets();
    }

    protected function registerWidgets()
    {
        foreach ($this->widgets as $widgetClass => $position) {
            DashboardWidgets::define($widgetClass, $position);
        }
    }

}
