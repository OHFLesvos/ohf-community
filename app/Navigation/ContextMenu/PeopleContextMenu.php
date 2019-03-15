<?php

namespace App\Navigation\ContextMenu;

use App\Person;

use Illuminate\Support\Facades\Auth;

class PeopleContextMenu extends BaseContextMenu {

    protected $routeName = 'people.index';

    public function getItems(): array
    {
        return [
            [
                'url' => route('people.duplicates'),
                'caption' => __('people.find_duplicates'),
                'icon' => 'exchange',
                'authorized' => Auth::user()->can('cleanup', Person::class)
            ],                    
            [
                'url' => route('people.import'),
                'caption' => __('app.import'),
                'icon' => 'upload',
                'authorized' => Auth::user()->can('create', Person::class)
            ],
        ];
    }

}
