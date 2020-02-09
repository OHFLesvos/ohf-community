<?php

namespace App\Navigation\ContextButtons\Collaboration;

use App\Navigation\ContextButtons\ContextButtons;

use App\Models\Collaboration\WikiArticle;

use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;

class LatestChangesContextButtons implements ContextButtons {

    public function getItems(View $view): array
    {
        return [
            'back' => [
                'url' => route('kb.index'),
                'caption' => __('app.close'),
                'icon' => 'times-circle',
                'authorized' => Auth::user()->can('list', WikiArticle::class),
            ]
        ];
    }

}
