<?php

namespace App\Services;

use Illuminate\View\View;

class ContextButtonsService
{
    private $buttons = [];

    public function define(string $routeName, $buttonsClass)
    {
        $this->buttons[$routeName] = $buttonsClass;
    }

    public function get(string $routeName, View $view): array
    {
        if (isset($this->buttons[$routeName])) {
            $buttons = new $this->buttons[$routeName]();
            return collect($buttons->getItems($view))->filter()->toArray();
        }
        return [];
    }
}
