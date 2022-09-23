<?php

namespace App\Providers\Traits;

use App\Support\Facades\ContextButtons;
use Exception;

trait RegisterContextButtons
{
    protected function registerContextButtons(): void
    {
        if (! isset($this->contextButtons)) {
            throw new Exception('$contextButtons not defined in '.__CLASS__);
        }

        foreach ($this->contextButtons as $routeName => $buttonsClass) {
            ContextButtons::define($routeName, $buttonsClass);
        }
    }
}
