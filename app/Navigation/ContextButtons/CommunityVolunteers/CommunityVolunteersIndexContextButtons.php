<?php

namespace App\Navigation\ContextButtons\CommunityVolunteers;

use App\Models\CommunityVolunteers\CommunityVolunteer;
use App\Navigation\ContextButtons\ContextButtons;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class CommunityVolunteersIndexContextButtons implements ContextButtons
{
    public function getItems(View $view): array
    {
        return [
            'action' => [
                'url' => route('cmtyvol.create'),
                'caption' => __('app.register'),
                'icon' => 'plus-circle',
                'icon_floating' => 'plus',
                'authorized' => Auth::user()->can('create', CommunityVolunteer::class),
            ],
            'report' => [
                'url' => route('cmtyvol.report'),
                'caption' => __('app.report'),
                'icon' => 'chart-bar',
                'authorized' => Auth::user()->can('viewAny', CommunityVolunteer::class),
            ],
            'export' => [
                'url' => route('cmtyvol.export'),
                'caption' => __('app.export'),
                'icon' => 'download',
                'authorized' => Auth::user()->can('export', CommunityVolunteer::class),
            ],
        ];
    }

}
