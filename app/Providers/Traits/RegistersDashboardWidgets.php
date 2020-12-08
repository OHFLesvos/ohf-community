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

        $position = 0;
        foreach ($this->dashboardWidgets as $widgetClass) {
            DashboardWidgets::define($widgetClass, $position++);
        }
    }

}
