<?php

namespace App\Navigation\ContextButtons\Helpers;

use App\Navigation\ContextButtons\ContextButtons;

use App\Models\Helpers\Helper;
use App\Models\Helpers\Responsibility;

use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class ResponsibilitiesIndexContextButtons implements ContextButtons {

    public function getItems(View $view): array
    {
        return [
            'action' => [
                'url' => route('people.helpers.responsibilities.create'),
                'caption' => __('app.register'),
                'icon' => 'plus-circle',
                'icon_floating' => 'plus',
                'authorized' => Auth::user()->can('create', Responsibility::class)
            ],
            'back' => [
                'url' => route('people.helpers.index'),
                'caption' => __('app.close'),
                'icon' => 'times-circle',
                'authorized' => Auth::user()->can('list', Helper::class)
            ]
        ];
    }

}
