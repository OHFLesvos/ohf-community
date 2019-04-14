<?php

namespace Modules\KB\Navigation\ContextButtons;

use App\Navigation\ContextButtons\ContextButtons;

use Modules\KB\Entities\WikiArticle;

use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\URL;

class ArticleShowContextButtons implements ContextButtons {

    public function getItems(View $view): array
    {
        $article = $view->getData()['article'];
        
        $previous_route = previous_route();
        if (in_array($previous_route, ['kb.tag', 'kb.articles.index'])) {
            $back_url = URL::previous(); 
        } else {
            $back_url = route('kb.index');
        }

        return [
            'action' => [
                'url' => route('kb.articles.edit', $article),
                'caption' => __('app.edit'),
                'icon' => 'edit',
                'icon_floating' => 'pencil-alt',
                'authorized' => Auth::user()->can('update', $article),
            ],
            'delete' => [
                'url' => route('kb.articles.destroy', $article),
                'caption' => __('app.delete'),
                'icon' => 'trash',
                'authorized' => Auth::user()->can('delete', $article),
                'confirmation' => __('kb::wiki.confirm_delete_article'),
            ],
            'back' => [
                'url' => $back_url,
                'caption' => __('app.close'),
                'icon' => 'times-circle',
                'authorized' => Auth::user()->can('list', WikiArticle::class),
            ]
        ];
    }

}
