<?php

namespace App\Navigation\ContextMenu;

interface ContextMenu {

    /**
     * @return array
     */
    public function getRouteNames(): array;

    /**
     * @return array
     */
    public function getItems(): array;

}
