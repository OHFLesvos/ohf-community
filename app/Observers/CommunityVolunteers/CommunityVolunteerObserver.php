<?php

namespace App\Observers\CommunityVolunteers;

use App\Models\CommunityVolunteers\CommunityVolunteer;
use Illuminate\Support\Facades\Storage;

class CommunityVolunteerObserver
{
    public function deleted(CommunityVolunteer $communityVolunteer): void
    {
        if ($communityVolunteer->portrait_picture !== null) {
            Storage::delete($communityVolunteer->portrait_picture);
        }
    }
}
