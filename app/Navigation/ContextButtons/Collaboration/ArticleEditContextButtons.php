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
                'caption' => __('Delete'),
                'icon' => 'trash',
                'authorized' => Auth::user()->can('delete', $article),
                'confirmation' => __('Do you really want to delete this article?'),
            ],
            'back' => [
                'url' => route('kb.articles.show', $article),
                'caption' => __('Cancel'),
                'icon' => 'times-circle',
                'authorized' => Auth::user()->can('view', $article),
            ],
        ];
    }
}
