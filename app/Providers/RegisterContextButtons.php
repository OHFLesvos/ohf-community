<?php

namespace App\Providers;

use App\Support\Facades\ContextButtons;

trait RegisterContextButtons
{
    protected function registerContextButtons()
    {
        if (!isset($this->contextButtons)) {
            throw new \Exception('$contextMenus not defined in ' . __CLASS__);
        }

        foreach ($this->contextButtons as $buttonsClass) {
            ContextButtons::define($buttonsClass);
        }
    }

}
