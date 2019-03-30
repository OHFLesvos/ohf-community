<?php

namespace Modules\Wiki\Navigation\ContextButtons;

use App\Navigation\ContextButtons\ContextButtons;

use Modules\Wiki\Entities\WikiArticle;

use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;

class WikiArticleShowContextButtons implements ContextButtons {

    public function getItems(View $view): array
    {
        $article = $view->getData()['article'];
        return [
            'action' => [
                'url' => route('wiki.articles.edit', $article),
                'caption' => __('app.edit'),
                'icon' => 'pencil',
                'icon_floating' => 'pencil',
                'authorized' => Auth::user()->can('update', $article)
            ],
            'delete' => [
                'url' => route('wiki.articles.destroy', $article),
                'caption' => __('app.delete'),
                'icon' => 'trash',
                'authorized' => Auth::user()->can('delete', $article),
                'confirmation' => __('wiki::wiki.confirm_delete_article')
            ],
            'back' => [
                'url' => route('wiki.articles.index'),
                'caption' => __('app.close'),
                'icon' => 'times-circle',
                'authorized' => Auth::user()->can('list', WikiArticle::class)
            ]
        ];
    }

}
