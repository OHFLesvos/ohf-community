<?php

namespace Modules\Library\Navigation\ContextButtons;

use App\Navigation\ContextButtons\ContextButtons;

use Modules\People\Entities\Person;

use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;

class LibraryLendingPersonLogContextButtons implements ContextButtons {

    public function getItems(View $view): array
    {
        $person = $view->getData()['person'];
        return [
            'back' => [
                'url' => route('library.lending.person', $person),
                'caption' => __('app.close'),
                'icon' => 'times-circle',
                'authorized' => Auth::user()->can('list', Person::class),
            ]
        ];
    }

}
