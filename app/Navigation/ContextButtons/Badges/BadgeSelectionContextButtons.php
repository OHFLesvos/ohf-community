<?php

namespace App\Navigation\ContextButtons\Badges;

use App\Navigation\ContextButtons\ContextButtons;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

class BadgeSelectionContextButtons implements ContextButtons
{
    public function getItems(View $view): array
    {
        return [
            'back' => [
                'url' => route('badges.index'),
                'caption' => __('Cancel'),
                'icon' => 'circle-xmark',
                'authorized' => Gate::allows('create-badges'),
            ],
        ];
    }
}
