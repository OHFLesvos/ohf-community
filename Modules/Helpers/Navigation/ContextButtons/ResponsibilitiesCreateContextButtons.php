<?php

namespace Modules\Helpers\Navigation\ContextButtons;

use App\Navigation\ContextButtons\ContextButtons;

use Modules\Helpers\Entities\Responsibility;

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
