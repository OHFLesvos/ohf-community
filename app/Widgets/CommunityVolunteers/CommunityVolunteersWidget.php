<?php

namespace App\Widgets\CommunityVolunteers;

use App\Models\CommunityVolunteers\CommunityVolunteer;
use App\Widgets\Widget;
use Illuminate\Support\Facades\Auth;

class CommunityVolunteersWidget implements Widget
{
    public function authorize(): bool
    {
        return Auth::user()->can('viewAny', CommunityVolunteer::class);
    }

    public function view(): string
    {
        return 'cmtyvol.dashboard.widgets.cmtyvol';
    }

    public function args(): array
    {
        return [
            'active' => CommunityVolunteer::workStatus('active')->count(),
        ];
    }
}
