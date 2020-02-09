<?php

namespace App\Navigation\ContextButtons\Collaboration;

use App\Navigation\ContextButtons\ContextButtons;

use App\Models\Collaboration\WikiArticle;

use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;

class ArticleCreateContextButtons implements ContextButtons {

    public function getItems(View $view): array
    {
        $previous_route = previous_route();
        return [
            'back' => [
                'url' => route($previous_route == 'kb.articles.index' ? 'kb.articles.index' : 'kb.index'),
                'caption' => __('app.cancel'),
                'icon' => 'times-circle',
                'authorized' => Auth::user()->can('list', WikiArticle::class),
            ]
        ];
    }

}
