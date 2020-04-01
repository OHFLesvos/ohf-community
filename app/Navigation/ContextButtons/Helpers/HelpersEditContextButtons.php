<?php

namespace App\Navigation\ContextButtons\Helpers;

use App\Navigation\ContextButtons\ContextButtons;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class HelpersEditContextButtons implements ContextButtons
{
    public function getItems(View $view): array
    {
        $helper = $view->getData()['helper'];
        return [
            'back' => [
                'url' => route('people.helpers.show', $helper),
                'caption' => __('app.cancel'),
                'icon' => 'times-circle',
                'authorized' => Auth::user()->can('view', $helper),
            ],
        ];
    }

}
