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
            'back' => [
                'url' => route('kb.articles.show', $article),
                'caption' => __('app.cancel'),
                'icon' => 'times-circle',
                'authorized' => Auth::user()->can('view', $article),
            ]
        ];
    }

}
