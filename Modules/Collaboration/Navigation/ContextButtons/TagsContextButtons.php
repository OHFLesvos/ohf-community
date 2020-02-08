<?php

namespace Modules\Collaboration\Navigation\ContextButtons;

use App\Navigation\ContextButtons\ContextButtons;

use Modules\Collaboration\Entities\WikiArticle;

use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;

class TagsContextButtons implements ContextButtons {

    public function getItems(View $view): array
    {
        return [
            'back' => [
                'url' => route('kb.index'),
                'caption' => __('app.overview'),
                'icon' => 'list',
                'authorized' => Auth::user()->can('list', WikiArticle::class),
            ]
        ];
    }

}
