<?php

namespace App\Navigation\ContextButtons\Collaboration;

use App\Models\Collaboration\WikiArticle;
use App\Navigation\ContextButtons\ContextButtons;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class TagsContextButtons implements ContextButtons
{
    public function getItems(View $view): array
    {
        return [
            'back' => [
                'url' => route('kb.index'),
                'caption' => __('Overview'),
                'icon' => 'list',
                'authorized' => Auth::user()->can('viewAny', WikiArticle::class),
            ],
        ];
    }
}
