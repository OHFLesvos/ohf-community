<?php

namespace App\Navigation\ContextButtons\Library;

use App\Models\People\Person;
use App\Navigation\ContextButtons\ContextButtons;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class LibraryLendingIndexContextButtons implements ContextButtons
{
    public function getItems(View $view): array
    {
        return [
            'export' => [
                'url' => route('library.lending.export'),
                'caption' => __('app.export'),
                'icon' => 'download',
                'authorized' => Auth::user()->can('list', Person::class),
            ],
        ];
    }
}
