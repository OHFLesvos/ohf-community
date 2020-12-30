<?php

namespace App\Navigation\ContextButtons\CommunityVolunteers;

use App\Models\CommunityVolunteers\CommunityVolunteer;
use App\Models\CommunityVolunteers\Responsibility;
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
            'import-export' => [
                'url' => route('cmtyvol.import-export'),
                'caption' => __('app.import_export'),
                'icon' => 'sync',
                'authorized' => Auth::user()->can('import', CommunityVolunteer::class) || Auth::user()->can('export', CommunityVolunteer::class),
            ],
            'responsibilities' => [
                'url' => route('cmtyvol.responsibilities.index'),
                'caption' => __('responsibilities.responsibilities'),
                'icon' => 'tasks',
                'authorized' => Auth::user()->can('viewAny', Responsibility::class),
            ],
        ];
    }

}
