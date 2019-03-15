<?php

namespace App\Navigation\ContextButtons;

use Illuminate\View\View;

interface ContextButtons {

    /**
     * @return array
     */
    public function getRouteNames(): array;

    /**
     * @param View $view
     * @return array
     */
    public function getItems(View $view): array;

}
