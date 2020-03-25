<?php

namespace App\Support\Facades;

use App\Services\DashboardWidgetsService;
use Illuminate\Support\Facades\Facade;

class DashboardWidgets extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return DashboardWidgetsService::class;
    }
}
