<?php

namespace App\Navigation\ContextButtons;

use Illuminate\View\View;

interface ContextButtons
{
    /**
     * @param View $view
     *
     * @return array
     */
    public function getItems(View $view): array;

}
