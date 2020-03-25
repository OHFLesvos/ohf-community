<?php

namespace App\Providers\Traits;

use App\Support\Facades\ContextMenus;
use Exception;

trait RegisterContextMenus
{
    protected function registerContextMenus()
    {
        if (! isset($this->contextMenus)) {
            throw new Exception('$contextMenus not defined in ' . __CLASS__);
        }

        foreach ($this->contextMenus as $routeName => $menuClass) {
            ContextMenus::define($routeName, $menuClass);
        }
    }

}
