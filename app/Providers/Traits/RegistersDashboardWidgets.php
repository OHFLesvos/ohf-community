<?php

namespace App\Providers\Traits;

use App\Support\Facades\DashboardWidgets;
use Exception;

trait RegistersDashboardWidgets
{
    protected function registerDashboardWidgets()
    {
        if (! isset($this->dashboardWidgets)) {
            throw new Exception('$dashboardWidgets not defined in ' . __CLASS__);
        }

        foreach ($this->dashboardWidgets as $widgetClass => $position) {
            DashboardWidgets::define($widgetClass, $position);
        }
    }

}
