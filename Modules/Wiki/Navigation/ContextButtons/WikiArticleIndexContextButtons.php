<?php

namespace Modules\Wiki\Navigation\ContextButtons;

use App\Navigation\ContextButtons\ContextButtons;

use Modules\Wiki\Entities\WikiArticle;

use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class WikiArticleIndexContextButtons implements ContextButtons {

    public function getItems(View $view): array
    {
        return [
            'action' => [
                'url' => route('wiki.articles.create'),
                'caption' => __('app.add'),
                'icon' => 'plus-circle',
                'icon_floating' => 'plus',
                'authorized' => Auth::user()->can('create', WikiArticle::class)
            ],
            'latestChanges' => [
                'url' => route('wiki.articles.latestChanges'),
                'caption' => __('app.latest_changes'),
                'icon' => 'history',
                'authorized' => Auth::user()->can('list', WikiArticle::class)
            ],
        ];
    }

}
