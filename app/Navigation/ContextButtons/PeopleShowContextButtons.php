<?php

namespace App\Navigation\ContextButtons;

use App\Person;

use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;

class PeopleShowContextButtons implements ContextButtons {

    public function getItems(View $view): array
    {
        $person = $view->getData()['person'];
        return [
            'action' => [
                'url' => route('people.edit', $person),
                'caption' => __('app.edit'),
                'icon' => 'pencil',
                'icon_floating' => 'pencil',
                'authorized' => Auth::user()->can('update', $person)
            ],
            'relations' => [
                'url' => route('people.relations', $person),
                'caption' => __('people.relationships'),
                'icon' => 'users',
                'authorized' => Auth::user()->can('update', $person)
            ],
            'helper' => $person->helper != null ? [
                'url' => route('people.helpers.show', $person->helper),
                'caption' => __('people.view_helper'),
                'icon' => 'id-badge',
                'authorized' => Auth::user()->can('view', $person->helper),
            ] : null,
            'delete' => [
                'url' => route('people.destroy', $person),
                'caption' => __('app.delete'),
                'icon' => 'trash',
                'authorized' => Auth::user()->can('delete', $person),
                'confirmation' => 'Really delete this person?'
            ],
            'back' => [
                'url' => route(session('peopleOverviewRouteName', 'people.index')),
                'caption' => __('app.close'),
                'icon' => 'times-circle',
                'authorized' => Auth::user()->can('list', Person::class)
            ]
        ];
    }

}
