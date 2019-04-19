<?php

namespace Modules\KB\Navigation\ContextButtons;

use App\Navigation\ContextButtons\ContextButtons;

use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;

class ArticleEditContextButtons implements ContextButtons {

    public function getItems(View $view): array
    {
        $article = $view->getData()['article'];
        return [
            'delete' => [
                'url' => route('kb.articles.destroy', $article),
                'caption' => __('app.delete'),
                'icon' => 'trash',
                'authorized' => Auth::user()->can('delete', $article),
                'confirmation' => __('kb::wiki.confirm_delete_article'),
            ],            
            'back' => [
                'url' => route('kb.articles.show', $article),
                'caption' => __('app.cancel'),
                'icon' => 'times-circle',
                'authorized' => Auth::user()->can('view', $article),
            ]
        ];
    }

}
