<?php

namespace App\Navigation\ContextButtons\Collaboration;

use App\Models\Collaboration\WikiArticle;
use App\Navigation\ContextButtons\ContextButtons;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class IndexContextButtons implements ContextButtons
{
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
            'latestChanges' => [
                'url' => route('kb.latestChanges'),
                'caption' => __('app.latest_changes'),
                'icon' => 'history',
                'authorized' => Auth::user()->can('viewAny', WikiArticle::class),
            ],
        ];
    }
}
