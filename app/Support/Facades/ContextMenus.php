<?php

namespace App\Support\Facades;

use App\Services\ContextMenusService;
use Illuminate\Support\Facades\Facade;

class ContextMenus extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return ContextMenusService::class;
    }
}
