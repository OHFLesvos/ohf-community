<?php

namespace App\Navigation\ContextButtons\Helpers;

use App\Models\Helpers\Helper;
use App\Navigation\ContextButtons\ContextButtons;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class HelperIndexContextButtons implements ContextButtons
{
    public function getItems(View $view): array
    {
        return [
            'action' => [
                'url' => route('people.helpers.createFrom'),
                'caption' => __('app.register'),
                'icon' => 'plus-circle',
                'icon_floating' => 'plus',
                'authorized' => Auth::user()->can('create', Helper::class),
            ],
            'report' => [
                'url' => route('people.helpers.report'),
                'caption' => __('app.report'),
                'icon' => 'chart-bar',
                'authorized' => Auth::user()->can('list', Helper::class),
            ],
            'export' => [
                'url' => route('people.helpers.export'),
                'caption' => __('app.export'),
                'icon' => 'download',
                'authorized' => Auth::user()->can('export', Helper::class),
            ],
        ];
    }

}
