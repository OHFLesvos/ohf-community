<?php

namespace App\Services;

class ContextMenusService {

    private $menus = [];

    public function define($menuClass)
    {
        $menu = new $menuClass();
        foreach ($menu->getRouteNames() as $routeName) {
            $this->menus[$routeName] = $menu;
        }
    }

    public function get($routeName)
    {
        return isset($this->menus[$routeName]) ? $this->menus[$routeName]->getItems() : [];
    }

}
