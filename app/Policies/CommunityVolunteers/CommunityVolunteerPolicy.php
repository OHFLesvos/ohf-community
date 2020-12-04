<?php

namespace App\Policies\CommunityVolunteers;

use App\Models\CommunityVolunteers\CommunityVolunteer;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class CommunityVolunteerPolicy
{
    use HandlesAuthorization;

    public function before($user, $ability)
    {
        if ($user->isSuperAdmin()) {
            return true;
        }
    }

    /**
     * Determine whether the user can list community volunteers.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function viewAny(User $user)
    {
        return $user->hasPermission('cmtyvol.view');
    }

    /**
     * Determine whether the user can export community volunteers.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function export(User $user)
    {
        return $user->hasPermission('cmtyvol.view');
    }

    /**
     * Determine whether the user can import community volunteers.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function import(User $user)
    {
        return $user->hasPermission('cmtyvol.manage');
    }

    /**
     * Determine whether the user can view the community volunteer.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\CommunityVolunteers\CommunityVolunteer  $communityVolunteer
     * @return mixed
     */
    public function view(User $user, CommunityVolunteer $communityVolunteer)
    {
        return $user->hasPermission('cmtyvol.view');
    }

    /**
     * Determine whether the user can create community volunteers.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return $user->hasPermission('cmtyvol.manage');
    }

    /**
     * Determine whether the user can update the community volunteer.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\CommunityVolunteers\CommunityVolunteer  $communityVolunteer
     * @return mixed
     */
    public function update(User $user, CommunityVolunteer $communityVolunteer)
    {
        return $user->hasPermission('cmtyvol.manage');
    }

    /**
     * Determine whether the user can delete the community volunteer.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\CommunityVolunteers\CommunityVolunteer  $communityVolunteer
     * @return mixed
     */
    public function delete(User $user, CommunityVolunteer $communityVolunteer)
    {
        return $user->hasPermission('cmtyvol.manage');
    }

    /**
     * Determine whether the user can restore the community volunteer.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\CommunityVolunteers\CommunityVolunteer  $communityVolunteer
     * @return mixed
     */
    public function restore(User $user, CommunityVolunteer $communityVolunteer)
    {
        return $user->hasPermission('cmtyvol.manage');
    }

    /**
     * Determine whether the user can permanently delete the community volunteer.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\CommunityVolunteers\CommunityVolunteer  $communityVolunteer
     * @return mixed
     */
    public function forceDelete(User $user, CommunityVolunteer $communityVolunteer)
    {
        return $user->hasPermission('cmtyvol.manage');
    }
}
