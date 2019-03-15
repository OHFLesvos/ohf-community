<?php

namespace App\Services;

use Illuminate\View\View;

class ContextButtonsService {

    private $buttons = [];

    public function define(string $routeName, $buttonsClass)
    {
        $this->buttons[$routeName] = $buttonsClass;
    }

    public function get(string $routeName, View $view)
    {
        if (isset($this->buttons[$routeName])) {
            $buttons = new $this->buttons[$routeName]();
            return $buttons->getItems($view);
        }
        return [];
    }

}
