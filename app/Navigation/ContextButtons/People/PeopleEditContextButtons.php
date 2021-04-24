<?php

namespace App\Navigation\ContextButtons\People;

use App\Navigation\ContextButtons\ContextButtons;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class PeopleEditContextButtons implements ContextButtons
{
    public function getItems(View $view): array
    {
        $person = $view->getData()['person'];
        return [
            'back' => [
                'url' => route('people.show', $person),
                'caption' => __('app.cancel'),
                'icon' => 'times-circle',
                'authorized' => Auth::user()->can('view', $person),
            ],
        ];
    }
}
