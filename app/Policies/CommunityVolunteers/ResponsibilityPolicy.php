<?php

namespace App\Policies\CommunityVolunteers;

use App\Models\CommunityVolunteers\Responsibility;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ResponsibilityPolicy
{
    use HandlesAuthorization;

    public function before($user, $ability)
    {
        if ($user->isSuperAdmin() && $ability != 'delete') {
            return true;
        }
    }

    /**
     * Determine whether the user can list responsibilities.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function viewAny(User $user)
    {
        return $user->hasPermission('cmtyvol.view');
    }

    /**
     * Determine whether the user can view the responsibility.
     *
     * @param  \App\User  $user
     * @param  \App\Models\CommunityVolunteers\Responsibility  $responsibility
     * @return mixed
     */
    public function view(User $user, Responsibility $responsibility)
    {
        return $user->hasPermission('cmtyvol.view');
    }

    /**
     * Determine whether the user can create responsibilities.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return $user->hasPermission('cmtyvol.manage');
    }

    /**
     * Determine whether the user can update the responsibility.
     *
     * @param  \App\User  $user
     * @param  \App\Models\CommunityVolunteers\Responsibility  $responsibility
     * @return mixed
     */
    public function update(User $user, Responsibility $responsibility)
    {
        return $user->hasPermission('cmtyvol.manage');
    }

    /**
     * Determine whether the user can delete the responsibility.
     *
     * @param  \App\User  $user
     * @param  \App\Models\CommunityVolunteers\Responsibility  $responsibility
     * @return mixed
     */
    public function delete(User $user, Responsibility $responsibility)
    {
        if ($responsibility->communityVolunteers()->count() > 0) {
            return false;
        }
        return $user->isSuperAdmin() || $user->hasPermission('cmtyvol.manage');
    }

}
