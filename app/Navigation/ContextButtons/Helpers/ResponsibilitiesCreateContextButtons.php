<?php

namespace App\Navigation\ContextButtons\Helpers;

use App\Navigation\ContextButtons\ContextButtons;

use App\Models\Helpers\Responsibility;

use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;

class ResponsibilitiesCreateContextButtons implements ContextButtons {

    public function getItems(View $view): array
    {
        return [
            'back' => [
                'url' => route('people.helpers.responsibilities.index'),
                'caption' => __('app.cancel'),
                'icon' => 'times-circle',
                'authorized' => Auth::user()->can('list', Responsibility::class)
            ]
        ];
    }

}
