<?php

namespace App\Navigation\ContextButtons\Helpers;

use App\Navigation\ContextButtons\ContextButtons;

use App\Models\Helpers\Helper;

use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;

class HelpersReturnToIndexContextButtons implements ContextButtons {

    public function getItems(View $view): array
    {
        return [
            'back' => [
                'url' => route('people.helpers.index'),
                'caption' => __('app.cancel'),
                'icon' => 'times-circle',
                'authorized' => Auth::user()->can('list', Helper::class)
            ]
        ];
    }

}
