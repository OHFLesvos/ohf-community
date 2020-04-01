<?php

namespace App\Navigation\ContextButtons\Helpers;

use App\Models\Helpers\Helper;
use App\Navigation\ContextButtons\ContextButtons;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class HelpersReturnToIndexContextButtons implements ContextButtons
{
    public function getItems(View $view): array
    {
        return [
            'back' => [
                'url' => route('people.helpers.index'),
                'caption' => __('app.cancel'),
                'icon' => 'times-circle',
                'authorized' => Auth::user()->can('list', Helper::class),
            ],
        ];
    }

}
