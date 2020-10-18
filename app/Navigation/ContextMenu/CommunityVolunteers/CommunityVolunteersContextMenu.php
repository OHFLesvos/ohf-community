<?php

namespace App\Navigation\ContextMenu\CommunityVolunteers;

use App\Models\CommunityVolunteers\CommunityVolunteer;
use App\Models\CommunityVolunteers\Responsibility;
use App\Navigation\ContextMenu\ContextMenu;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

class CommunityVolunteersContextMenu implements ContextMenu
{
    public function getItems(View $view): array
    {
        return [
            'badges' => [
                'url' => route('badges.index', ['source' => 'cmtyvol']),
                'caption' => __('badges.badges'),
                'icon' => 'id-card',
                'authorized' => Auth::user()->can('viewAny', CommunityVolunteer::class) && Gate::allows('create-badges'),
            ],
            'import' => [
                'url' => route('cmtyvol.import'),
                'caption' => __('app.import'),
                'icon' => 'upload',
                'authorized' => Auth::user()->can('import', CommunityVolunteer::class),
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
