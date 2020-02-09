<?php

namespace App\Navigation\ContextButtons\People;

use App\Navigation\ContextButtons\ContextButtons;

use App\Models\People\Person;

use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;

class PeopleImportContextButtons implements ContextButtons {

    public function getItems(View $view): array
    {
        return [
            'back' => [
                'url' => route('people.index'),
                'caption' => __('app.cancel'),
                'icon' => 'times-circle',
                'authorized' => Auth::user()->can('list', Person::class)
            ]
        ];
    }

}
