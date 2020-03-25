<?php

namespace App\Navigation\ContextButtons\Helpers;

use App\Models\Helpers\Responsibility;
use App\Navigation\ContextButtons\ContextButtons;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class ResponsibilitiesCreateContextButtons implements ContextButtons
{
    public function getItems(View $view): array
    {
        return [
            'back' => [
                'url' => route('people.helpers.responsibilities.index'),
                'caption' => __('app.cancel'),
                'icon' => 'times-circle',
                'authorized' => Auth::user()->can('list', Responsibility::class),
            ],
        ];
    }

}
