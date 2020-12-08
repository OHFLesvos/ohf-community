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

    public function render()
    {
        return view('widgets.cmtyvol', [
            'active' => CommunityVolunteer::workStatus('active')->count(),
        ]);
    }
}
