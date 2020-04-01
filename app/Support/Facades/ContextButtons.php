<?php

namespace App\Support\Facades;

use App\Services\ContextButtonsService;
use Illuminate\Support\Facades\Facade;

class ContextButtons extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return ContextButtonsService::class;
    }
}
