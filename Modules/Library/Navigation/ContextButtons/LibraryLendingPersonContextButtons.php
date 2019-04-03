<?php

namespace Modules\Library\Navigation\ContextButtons;

use App\Navigation\ContextButtons\ContextButtons;

use App\Person;

use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class LibraryLendingPersonContextButtons implements ContextButtons {

    public function getItems(View $view): array
    {
        $person = $view->getData()['person'];
        return [
            'log' => [
                'url' => route('library.lending.personLog', $person),
                'caption' => __('app.log'),
                'icon' => 'list',
                'authorized' => $person->bookLendings()->count() > 0 && Auth::user()->can('list', Person::class),
            ],
            'person' => [
                'url' => route('people.show', $person),
                'caption' => __('people.view_person'),
                'icon' => 'users',
                'authorized' => Auth::user()->can('view', $person),
            ],
            'back' => [
                'url' => route('library.lending.index'),
                'caption' => __('app.close'),
                'icon' => 'times-circle',
                'authorized' => Gate::allows('operate-library'),
            ],
        ];
    }

}
