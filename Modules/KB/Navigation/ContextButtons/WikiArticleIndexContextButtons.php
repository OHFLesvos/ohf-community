<?php

namespace Modules\KB\Navigation\ContextButtons;

use App\Navigation\ContextButtons\ContextButtons;

use Modules\KB\Entities\WikiArticle;

use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;

class WikiArticleIndexContextButtons implements ContextButtons {

    public function getItems(View $view): array
    {
        return [
            'action' => [
                'url' => route('kb.articles.create'),
                'caption' => __('app.add'),
                'icon' => 'plus-circle',
                'icon_floating' => 'plus',
                'authorized' => Auth::user()->can('create', WikiArticle::class)
            ],
            'latestChanges' => [
                'url' => route('kb.articles.latestChanges'),
                'caption' => __('app.latest_changes'),
                'icon' => 'history',
                'authorized' => Auth::user()->can('list', WikiArticle::class)
            ],
        ];
    }

}
