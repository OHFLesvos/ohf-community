<?php

namespace App\Navigation\ContextButtons\CommunityVolunteers;

use App\Models\CommunityVolunteers\CommunityVolunteer;
use App\Models\CommunityVolunteers\Responsibility;
use App\Navigation\ContextButtons\ContextButtons;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class ResponsibilitiesIndexContextButtons implements ContextButtons
{
    public function getItems(View $view): array
    {
        return [
            'action' => [
                'url' => route('cmtyvol.responsibilities.create'),
                'caption' => __('Register'),
                'icon' => 'circle-plus',
                'icon_floating' => 'plus',
                'authorized' => Auth::user()->can('create', Responsibility::class),
            ],
            'back' => [
                'url' => route('cmtyvol.index'),
                'caption' => __('Close'),
                'icon' => 'circle-xmark',
                'authorized' => Auth::user()->can('viewAny', CommunityVolunteer::class),
            ],
        ];
    }
}
