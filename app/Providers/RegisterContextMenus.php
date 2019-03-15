<?php

namespace App\Providers;

use App\Support\Facades\ContextMenus;

trait RegisterContextMenus
{
    protected function registerContextMenus()
    {
        if (!isset($this->contextMenus)) {
            throw new \Exception('$contextMenus not defined in ' . __CLASS__);
        }

        foreach ($this->contextMenus as $routeName => $menuClass) {
            ContextMenus::define($routeName, $menuClass);
        }
    }

}
