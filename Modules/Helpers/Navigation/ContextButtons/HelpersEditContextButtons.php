<?php

namespace Modules\Helpers\Navigation\ContextButtons;

use App\Navigation\ContextButtons\ContextButtons;

use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;

class HelpersEditContextButtons implements ContextButtons {

    public function getItems(View $view): array
    {
        $helper = $view->getData()['helper'];
        return [
            'back' => [
                'url' => route('people.helpers.show', $helper),
                'caption' => __('app.cancel'),
                'icon' => 'times-circle',
                'authorized' => Auth::user()->can('view', $helper)
            ]
        ];
    }

}
