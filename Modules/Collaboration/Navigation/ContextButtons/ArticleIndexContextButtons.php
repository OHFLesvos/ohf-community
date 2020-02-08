<?php

namespace Modules\Collaboration\Navigation\ContextButtons;

use App\Navigation\ContextButtons\ContextButtons;

use Modules\Collaboration\Entities\WikiArticle;

use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;

class ArticleIndexContextButtons implements ContextButtons {

    public function getItems(View $view): array
    {
        return [
            'action' => [
                'url' => route('kb.articles.create'),
                'caption' => __('app.add'),
                'icon' => 'plus-circle',
                'icon_floating' => 'plus',
                'authorized' => Auth::user()->can('create', WikiArticle::class),
            ],
            'back' => [
                'url' => route('kb.index'),
                'caption' => __('app.overview'),
                'icon' => 'list',
                'authorized' => Auth::user()->can('list', WikiArticle::class),
            ],
        ];
    }

}
