<?php

namespace App\Navigation\ContextButtons\People;

use App\Models\People\Person;
use App\Navigation\ContextButtons\ContextButtons;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class PeopleShowContextButtons implements ContextButtons
{
    public function getItems(View $view): array
    {
        $person = $view->getData()['person'];
        return [
            'action' => [
                'url' => route('people.edit', $person),
                'caption' => __('app.edit'),
                'icon' => 'edit',
                'icon_floating' => 'pencil-alt',
                'authorized' => Auth::user()->can('update', $person),
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
                'confirmation' => __('people.confirm_delete_person'),
            ],
            'back' => [
                'url' => route(session('peopleOverviewRouteName', 'people.index')),
                'caption' => __('app.close'),
                'icon' => 'times-circle',
                'authorized' => Auth::user()->can('list', Person::class),
            ],
        ];
    }
}
