<?php

namespace App\Navigation\ContextButtons\CommunityVolunteers;

use App\Models\CommunityVolunteers\Responsibility;
use App\Navigation\ContextButtons\ContextButtons;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class ResponsibilitiesCreateContextButtons implements ContextButtons
{
    public function getItems(View $view): array
    {
        return [
            'back' => [
                'url' => route('cmtyvol.responsibilities.index'),
                'caption' => __('Cancel'),
                'icon' => 'times-circle',
                'authorized' => Auth::user()->can('viewAny', Responsibility::class),
            ],
        ];
    }
}
