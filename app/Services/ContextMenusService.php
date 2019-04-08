<?php

namespace App\Services;

class ContextMenusService {

    private $menus = [];

    public function define(string $routeName, $menuClass)
    {
        $this->menus[$routeName] = $menuClass;
    }

    public function get(string $routeName)
    {
        if (isset($this->menus[$routeName])) {
            $menu = new $this->menus[$routeName]();
            return $menu->getItems();
        }
        return [];
    }

}
