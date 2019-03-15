<?php

namespace App\Navigation\ContextButtons;

use Illuminate\View\View;
use Illuminate\Support\Facades\Gate;

class LogisticsArticleEditContextButtons implements ContextButtons {

    public function getItems(View $view): array
    {
        $article = $view->getData()['article'];
        return [
            'delete' => [
                'url' => route('logistics.articles.destroyArticle', $article),
                'caption' => __('app.delete'),
                'icon' => 'trash',
                'authorized' => Gate::allows('use-logistics'),
                'confirmation' => 'Really delete this article?'
            ],
            'back' => [
                'url' => route('logistics.articles.index', $article->project),
                'caption' => __('app.cancel'),
                'icon' => 'times-circle',
                'authorized' => Gate::allows('use-logistics')
            ]
        ];
    }

}
