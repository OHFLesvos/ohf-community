<?php

namespace Modules\People\Navigation\ContextButtons;

use App\Navigation\ContextButtons\ContextButtons;

use Modules\People\Entities\Person;

use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;

class PeopleCreateContextButtons implements ContextButtons {

    public function getItems(View $view): array
    {
        return [
            'back' => [
                'url' => route(session('peopleOverviewRouteName', 'people.index')),
                'caption' => __('app.cancel'),
                'icon' => 'times-circle',
                'authorized' => Auth::user()->can('list', Person::class)
            ]
        ];

    }

}
