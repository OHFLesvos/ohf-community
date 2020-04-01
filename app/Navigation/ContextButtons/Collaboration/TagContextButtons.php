<?php

namespace App\Navigation\ContextButtons\Collaboration;

use App\Models\Collaboration\WikiArticle;
use App\Navigation\ContextButtons\ContextButtons;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class TagContextButtons implements ContextButtons
{
    public function getItems(View $view): array
    {
        $tag = $view->getData()['tag'];
        $previous_route = previous_route();
        return [
            'pdf' => [
                'url' => route('kb.tags.pdf', $tag),
                'caption' => __('app.pdf'),
                'icon' => 'file-pdf',
                'authorized' => Auth::user() != null && Auth::user()->can('list', WikiArticle::class),
            ],
            'back' => [
                'url' => route($previous_route == 'kb.tags' ? 'kb.tags' : 'kb.index'),
                'caption' => __('app.close'),
                'icon' => 'times-circle',
                'authorized' => Auth::user() != null && Auth::user()->can('list', WikiArticle::class),
            ],
        ];
    }

}
