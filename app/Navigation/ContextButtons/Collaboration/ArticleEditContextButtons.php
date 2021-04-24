<?php

namespace App\Navigation\ContextButtons\Collaboration;

use App\Navigation\ContextButtons\ContextButtons;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class ArticleEditContextButtons implements ContextButtons
{
    public function getItems(View $view): array
    {
        $article = $view->getData()['article'];
        return [
            'delete' => [
                'url' => route('kb.articles.destroy', $article),
                'caption' => __('app.delete'),
                'icon' => 'trash',
                'authorized' => Auth::user()->can('delete', $article),
                'confirmation' => __('wiki.confirm_delete_article'),
            ],
            'back' => [
                'url' => route('kb.articles.show', $article),
                'caption' => __('app.cancel'),
                'icon' => 'times-circle',
                'authorized' => Auth::user()->can('view', $article),
            ],
        ];
    }
}
