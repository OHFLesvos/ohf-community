<?php

namespace App\Navigation\ContextButtons\Collaboration;

use App\Models\Collaboration\WikiArticle;
use App\Navigation\ContextButtons\ContextButtons;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class ArticleIndexContextButtons implements ContextButtons
{
    public function getItems(View $view): array
    {
        return [
            'action' => [
                'url' => route('kb.articles.create'),
                'caption' => __('Add'),
                'icon' => 'plus-circle',
                'icon_floating' => 'plus',
                'authorized' => Auth::user()->can('create', WikiArticle::class),
            ],
            'back' => [
                'url' => route('kb.index'),
                'caption' => __('Overview'),
                'icon' => 'list',
                'authorized' => Auth::user()->can('viewAny', WikiArticle::class),
            ],
        ];
    }
}
