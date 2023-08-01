<?php

namespace App\Services;

class NavigationItemsService
{
    private $items = [];

    public function define($itemClass, int $position = null): void
    {
        $item = new $itemClass();
        $this->items[] = [
            'item' => $item,
            'position' => $position,
        ];
    }

    public function items()
    {
        return collect($this->items)
            ->sortBy('position')
            ->pluck('item');
    }
}
