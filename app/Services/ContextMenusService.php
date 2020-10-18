<?php

namespace App\Services;

use Illuminate\Support\Collection;
use Illuminate\View\View;

class ContextMenusService
{
    private $menus = [];

    public function define(string $routeName, $menuClass)
    {
        $this->menus[$routeName] = $menuClass;
    }

    public function get(string $routeName, View $view): Collection
    {
        if (isset($this->menus[$routeName])) {
            $menu = new $this->menus[$routeName]();
            return collect($menu->getItems($view));
        }
        return collect();
    }
}
