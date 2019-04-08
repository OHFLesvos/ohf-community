<?php

namespace Modules\People\Navigation\ContextButtons;

use App\Navigation\ContextButtons\ContextButtons;

use Modules\People\Entities\Person;

use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class PeopleIndexContextButtons implements ContextButtons {

    public function getItems(View $view): array
    {
        return [
            'action' => [
                'url' => route('people.create'),
                'caption' => __('app.register'),
                'icon' => 'plus-circle',
                'icon_floating' => 'plus',
                'authorized' => Auth::user()->can('create', Person::class)
            ],
            'report'=> [
                'url' => route('reporting.people'),
                'caption' => __('app.report'),
                'icon' => 'chart-line',
                'authorized' => Gate::allows('view-people-reports')
            ],
            'export' => [
                'url' => route('people.export'),
                'caption' => __('app.export'),
                'icon' => 'download',
                'authorized' => Auth::user()->can('export', Person::class)
            ],
        ];

    }

}
