<?php

namespace App\View\Widgets;

use App\Models\CommunityVolunteers\CommunityVolunteer;
use Illuminate\Support\Facades\Auth;

class CommunityVolunteersWidget implements Widget
{
    public function authorize(): bool
    {
        return Auth::user()->can('viewAny', CommunityVolunteer::class);
    }

    public function view(): string
    {
        return 'widgets.cmtyvol';
    }

    public function args(): array
    {
        return [
            'active' => CommunityVolunteer::workStatus('active')->count(),
        ];
    }
}
