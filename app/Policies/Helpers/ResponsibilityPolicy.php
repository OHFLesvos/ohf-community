<?php

namespace App\Policies\Helpers;

use App\Models\Helpers\Responsibility;
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
        return $user->hasPermission('people.helpers.view');
    }

    /**
     * Determine whether the user can view the responsibility.
     *
     * @param  \App\User  $user
     * @param  \App\Models\Helpers\Responsibility  $responsibility
     * @return mixed
     */
    public function view(User $user, Responsibility $responsibility)
    {
        return $user->hasPermission('people.helpers.view');
    }

    /**
     * Determine whether the user can create responsibilities.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return $user->hasPermission('people.helpers.manage');
    }

    /**
     * Determine whether the user can update the responsibility.
     *
     * @param  \App\User  $user
     * @param  \App\Models\Helpers\Responsibility  $responsibility
     * @return mixed
     */
    public function update(User $user, Responsibility $helper)
    {
        return $user->hasPermission('people.helpers.manage');
    }

    /**
     * Determine whether the user can delete the helper.
     *
     * @param  \App\User  $user
     * @param  \App\Models\Helpers\Responsibility  $responsibility
     * @return mixed
     */
    public function delete(User $user, Responsibility $responsibility)
    {
        if ($responsibility->helpers()->count() > 0) {
            return false;
        }
        return $user->isSuperAdmin() || $user->hasPermission('people.helpers.manage');
    }

}
