<?php

namespace App\Services;

use Illuminate\Support\Collection;

class ContextMenusService
{
    private $menus = [];

    public function define(string $routeName, $menuClass)
    {
        $this->menus[$routeName] = $menuClass;
    }

    public function get(string $routeName): Collection
    {
        if (isset($this->menus[$routeName])) {
            $menu = new $this->menus[$routeName]();
            return collect($menu->getItems());
        }
        return collect();
    }
}
