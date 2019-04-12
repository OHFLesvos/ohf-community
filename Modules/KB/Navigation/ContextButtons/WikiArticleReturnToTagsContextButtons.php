<?php

namespace Modules\KB\Navigation\ContextButtons;

use App\Navigation\ContextButtons\ContextButtons;

use Modules\KB\Entities\WikiArticle;

use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;

class WikiArticleReturnToTagsContextButtons implements ContextButtons {

    public function getItems(View $view): array
    {
        return [
            'back' => [
                'url' => route('kb.articles.tags'),
                'caption' => __('app.close'),
                'icon' => 'times-circle',
                'authorized' => Auth::user()->can('list', WikiArticle::class)
            ]
        ];
    }

}
