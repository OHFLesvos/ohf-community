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
            'import-export' => [
                'url' => route('cmtyvol.import-export'),
                'caption' => __('Import & Export'),
                'icon' => 'sync',
                'authorized' => Auth::user()->can('import', CommunityVolunteer::class) || Auth::user()->can('export', CommunityVolunteer::class),
            ],
        ];
    }
}
