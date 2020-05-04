<?php

namespace App\Navigation\ContextButtons\Library;

use App\Navigation\ContextButtons\ContextButtons;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

class LibraryReportContextButtons implements ContextButtons
{
    public function getItems(View $view): array
    {
        $previous_route = previous_route();
        return [
            'back' => [
                'url' => route($previous_route !== null && $previous_route != 'library.report'
                    ? $previous_route
                    : 'library.lending.index'),
                'caption' => __('app.close'),
                'icon' => 'times-circle',
                'authorized' => Gate::allows('operate-library'),
            ],
        ];
    }

}
