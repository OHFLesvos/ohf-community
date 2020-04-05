<?php

namespace App\Navigation\ContextButtons\Library;

use App\Navigation\ContextButtons\ContextButtons;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

class LibraryLendingIndexContextButtons implements ContextButtons
{
    public function getItems(View $view): array
    {
        return [
        ];
    }
}
