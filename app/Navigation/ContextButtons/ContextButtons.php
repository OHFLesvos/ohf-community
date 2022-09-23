<?php

namespace App\Navigation\ContextButtons;

use Illuminate\View\View;

interface ContextButtons
{
    public function getItems(View $view): array;
}
