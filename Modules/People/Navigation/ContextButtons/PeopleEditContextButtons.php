<?php

namespace Modules\People\Navigation\ContextButtons;

use App\Navigation\ContextButtons\ContextButtons;

use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;

class PeopleEditContextButtons implements ContextButtons {

    public function getItems(View $view): array
    {
        $person = $view->getData()['person'];
        $url = route('people.show', $person);
        if (preg_match('/bank\\/withdrawal\\/search/', url()->previous())) {
            $url = route('bank.withdrawalSearch');
        }
        return [
            'back' => [
                'url' => $url,
                'caption' => __('app.cancel'),
                'icon' => 'times-circle',
                'authorized' => Auth::user()->can('view', $person)
            ]
        ];

    }

}
