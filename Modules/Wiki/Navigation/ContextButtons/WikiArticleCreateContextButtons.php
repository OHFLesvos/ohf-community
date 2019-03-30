<?php

namespace Modules\Wiki\Navigation\ContextButtons;

use App\Navigation\ContextButtons\ContextButtons;

use Modules\Wiki\Entities\WikiArticle;

use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;

class WikiArticleCreateContextButtons implements ContextButtons {

    public function getItems(View $view): array
    {
        return [
            'back' => [
                'url' => route('wiki.articles.index'),
                'caption' => __('app.cancel'),
                'icon' => 'times-circle',
                'authorized' => Auth::user()->can('list', WikiArticle::class)
            ]
        ];
    }

}
