<?php

namespace App\Services;

use Illuminate\View\View;

class ContextButtonsService {

    private $buttons = [];

    public function define($buttonsClass)
    {
        $buttons = new $buttonsClass();
        foreach ($buttons->getRouteNames() as $routeName) {
            $this->buttons[$routeName] = $buttons;
        }
    }

    public function get(string $routeName, View $view)
    {
        return isset($this->buttons[$routeName]) ? $this->buttons[$routeName]->getItems($view) : [];
    }

}
