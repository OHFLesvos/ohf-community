<?php

namespace App\Providers\Traits;

use App\Support\Facades\NavigationItems;
use Exception;

trait RegistersNavigationItems
{
    protected function registerNavigationItems()
    {
        if (! isset($this->navigationItems)) {
            throw new Exception('$navigationItems not defined in ' . __CLASS__);
        }

        foreach ($this->navigationItems as $itemClass => $position) {
            NavigationItems::define($itemClass, $position);
        }
    }

}
