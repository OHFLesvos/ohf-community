<?php

namespace App\Support\Facades;

use App\Services\NavigationItemsService;
use Illuminate\Support\Facades\Facade;

class NavigationItems extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return NavigationItemsService::class;
    }
}
