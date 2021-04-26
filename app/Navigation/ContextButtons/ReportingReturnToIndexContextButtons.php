<?php

namespace App\Navigation\ContextButtons;

use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class ReportingReturnToIndexContextButtons implements ContextButtons
{
    public function getItems(View $view): array
    {
        return [
            'back' => [
                'url' => route('reports.index'),
                'caption' => __('Close'),
                'icon' => 'times-circle',
                'authorized' => Auth::user()->can('view-reports'),
            ],
        ];
    }
}
