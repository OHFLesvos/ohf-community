<?php

namespace Modules\People\Navigation\ContextMenu;

use App\Navigation\ContextMenu\ContextMenu;

use Modules\People\Entities\Person;

use Illuminate\Support\Facades\Auth;

class PeopleContextMenu implements ContextMenu {

    public function getItems(): array
    {
        return [
            [
                'url' => route('people.duplicates'),
                'caption' => __('people::people.find_duplicates'),
                'icon' => 'exchange-alt',
                'authorized' => Auth::user()->can('cleanup', Person::class)
            ],                    
            [
                'url' => route('people.export'),
                'caption' => __('app.export'),
                'icon' => 'download',
                'authorized' => Auth::user()->can('export', Person::class)
            ],            
            [
                'url' => route('people.import'),
                'caption' => __('app.import'),
                'icon' => 'upload',
                'authorized' => Auth::user()->can('create', Person::class)
            ],
            [
                'url' => route('people.bulkSearch'),
                'caption' => __('app.bulk_search'),
                'icon' => 'search',
                'authorized' => Auth::user()->can('list', Person::class)
            ],            
        ];
    }

}
