<?php

namespace App\Navigation\ContextButtons;

use Illuminate\View\View;

class ReportingArticleContextButtons implements ContextButtons {

    public function getItems(View $view): array
    {
        $article = $view->getData()['article'];
        return [
            'back' => [
                'url' => route('reporting.articles', $article->project),
                'caption' => __('app.close'),
                'icon' => 'times-circle',
                'authorized' => true
            ]
        ];
    }

}
