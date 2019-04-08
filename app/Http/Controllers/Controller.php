<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Controller extends BaseController
{
    use AuthorizesRequests {
        resourceAbilityMap as protected traitResourceAbilityMap;
    }
    use DispatchesJobs, ValidatesRequests;

    protected function resourceAbilityMap()
    {
        // Ensure method index is automatically authorized using policy method 'list' if authorizeResource() is used
        return array_merge($this->traitResourceAbilityMap(), ['index' => 'list']);
    }
}
