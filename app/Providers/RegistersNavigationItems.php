<?php

namespace App\Providers;

use App\Support\Facades\NavigationItems;

trait RegistersNavigationItems
{
    protected function registerNavigationItems()
    {
        if (!isset($this->navigationItems)) {
            throw new \Exception('$navigationItems not defined in ' . __CLASS__);
        }

        foreach ($this->navigationItems as $itemClass => $position) {
            NavigationItems::define($itemClass, $position);
        }
    }

}
