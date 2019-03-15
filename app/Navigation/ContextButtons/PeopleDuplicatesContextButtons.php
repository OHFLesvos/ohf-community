<?php

namespace App\Navigation\ContextButtons;

use App\Person;

use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;

class PeopleDuplicatesContextButtons implements ContextButtons {

    public function getItems(View $view): array
    {
        return [
            'back' => [
                'url' => route('people.index'),
                'caption' => __('app.close'),
                'icon' => 'times-circle',
                'authorized' => Auth::user()->can('list', Person::class)
            ]
        ];
    }

}
