<?php

namespace Modules\Bank\Navigation\ContextButtons;

use App\Navigation\ContextButtons\ContextButtons;

use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;

class PeopleEditContextButtons implements ContextButtons {

    public function getItems(View $view): array
    {
        $person = $view->getData()['person'];
        return [
            'back' => [
                'url' => route('bank.people.show', $person),
                'caption' => __('app.cancel'),
                'icon' => 'times-circle',
                'authorized' => Auth::user()->can('view', $person)
            ]
        ];

    }

}
