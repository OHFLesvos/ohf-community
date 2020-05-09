<?php

namespace App\Navigation\ContextMenu\People;

use App\Models\People\Person;
use App\Navigation\ContextMenu\ContextMenu;
use Illuminate\Support\Facades\Auth;

class PeopleContextMenu implements ContextMenu
{
    public function getItems(): array
    {
        return [
            [
                'url' => route('people.duplicates'),
                'caption' => __('people.find_duplicates'),
                'icon' => 'exchange-alt',
                'authorized' => Auth::user()->can('cleanup', Person::class),
            ],
            [
                'url' => route('people.export'),
                'caption' => __('app.export'),
                'icon' => 'download',
                'authorized' => Auth::user()->can('export', Person::class),
            ],
            [
                'url' => route('people.import'),
                'caption' => __('app.import'),
                'icon' => 'upload',
                'authorized' => Auth::user()->can('create', Person::class),
            ],
            [
                'url' => route('people.bulkSearch'),
                'caption' => __('app.bulk_search'),
                'icon' => 'search',
                'authorized' => Auth::user()->can('viewAny', Person::class),
            ],
        ];
    }

}
