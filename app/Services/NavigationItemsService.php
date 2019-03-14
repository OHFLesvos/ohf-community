<?php

namespace App\Services;

class NavigationItemsService {

    private $items = [];

    public function define($itemClass, int $position = null)
    {
        $item = new $itemClass();
        if ($position !== null) {
            array_splice($this->items, $position, 0, [$item]);
        } else {
            $this->items[] = $item;
        }
    }

    public function items()
    {
        return $this->items;
    }

}
