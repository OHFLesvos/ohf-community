<?php

namespace Modules\People\Navigation\ContextMenu;

use App\Person;
use App\Navigation\ContextMenu\ContextMenu;

use Illuminate\Support\Facades\Auth;

class PeopleContextMenu implements ContextMenu {

    public function getItems(): array
    {
        return [
            [
                'url' => route('people.duplicates'),
                'caption' => __('people::people.find_duplicates'),
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
