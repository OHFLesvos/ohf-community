<?php

namespace App\Navigation\ContextMenu;

use Illuminate\View\View;

interface ContextMenu
{
    public function getItems(View $view): array;
}
