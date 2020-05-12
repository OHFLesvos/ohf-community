<?php

namespace App\Services;

use Illuminate\Support\Collection;
use Illuminate\View\View;

class ContextButtonsService
{
    private $buttons = [];

    public function define(string $routeName, $buttonsClass)
    {
        $this->buttons[$routeName] = $buttonsClass;
    }

    public function get(string $routeName, View $view): Collection
    {
        if (isset($this->buttons[$routeName])) {
            $buttons = new $this->buttons[$routeName]();
            return collect($buttons->getItems($view))->filter();
        }
        return collect();
    }
}
