<?php

namespace App\Providers\Traits;

use App\Support\Facades\NavigationItems;
use Exception;

trait RegistersNavigationItems
{
    protected function registerNavigationItems(): void
    {
        if (! isset($this->navigationItems)) {
            throw new Exception('$navigationItems not defined in '.__CLASS__);
        }

        $position = 0;
        foreach ($this->navigationItems as $itemClass) {
            NavigationItems::define($itemClass, $position++);
        }
    }
}
