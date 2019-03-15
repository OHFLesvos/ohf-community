<?php

namespace App\Navigation\ContextButtons;

use Illuminate\View\View;
use Illuminate\Support\Facades\Gate;

class LogisticsArticleIndexContextButtons implements ContextButtons {

    public function getItems(View $view): array
    {
        $project = $view->getData()['project'];
        return [
            'report'=> [
                'url' => route('reporting.articles', $project),
                'caption' => __('app.report'),
                'icon' => 'line-chart',
                'authorized' => true // TODO
            ],
            'back' => [
                'url' => route('logistics.index'),
                'caption' => __('app.close'),
                'icon' => 'times-circle',
                'authorized' => Gate::allows('use-logistics')
            ]
        ];
    }

}
