<?php

namespace Modules\KB\Navigation\ContextButtons;

use App\Navigation\ContextButtons\ContextButtons;

use Modules\KB\Entities\WikiArticle;

use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;

class TagContextButtons implements ContextButtons {

    public function getItems(View $view): array
    {
        $previous_route = previous_route();
        return [
            'back' => [
                'url' => route($previous_route == 'kb.tags' ? 'kb.tags' : 'kb.index'),
                'caption' => __('app.close'),
                'icon' => 'times-circle',
                'authorized' => Auth::user()->can('list', WikiArticle::class),
            ]
        ];
    }

}
