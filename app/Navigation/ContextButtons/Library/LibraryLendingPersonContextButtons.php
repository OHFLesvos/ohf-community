<?php

namespace App\Navigation\ContextButtons\Library;

use App\Navigation\ContextButtons\ContextButtons;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

class LibraryLendingPersonContextButtons implements ContextButtons
{
    public function getItems(View $view): array
    {
        $person = $view->getData()['person'];
        return [
            'person' => [
                'url' => route('people.show', $person),
                'caption' => __('people.view_person'),
                'icon' => 'users',
                'authorized' => Auth::user()->can('view', $person),
            ],
            'back' => [
                'url' => route('library.lending.index'),
                'caption' => __('app.close'),
                'icon' => 'times-circle',
                'authorized' => Gate::allows('operate-library'),
            ],
        ];
    }

}
