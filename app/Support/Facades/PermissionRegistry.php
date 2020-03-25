<?php

namespace App\Support\Facades;

use App\Services\PermissionRegistryService;
use Illuminate\Support\Facades\Facade;

class PermissionRegistry extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return PermissionRegistryService::class;
    }
}
