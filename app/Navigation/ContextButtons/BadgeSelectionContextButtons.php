<?php

namespace App\Navigation\ContextButtons;

use Illuminate\View\View;
use Illuminate\Support\Facades\Gate;

class BadgeSelectionContextButtons implements ContextButtons {

    public function getItems(View $view): array
    {
        return [
            'back' => [
                'url' => route('badges.index'),
                'caption' => __('app.cancel'),
                'icon' => 'times-circle',
                'authorized' => Gate::allows('create-badges'),
            ],
        ];
    }

}
