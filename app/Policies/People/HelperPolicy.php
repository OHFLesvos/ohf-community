<?php

namespace App\Policies\People;

use App\User;
use App\Helper;
use Illuminate\Auth\Access\HandlesAuthorization;

class HelperPolicy
{
    use HandlesAuthorization;

    public function before($user, $ability)
    {
        if ($user->isSuperAdmin()) {
            return true;
        }
    }

    /**
     * Determine whether the user can list helpers.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function list(User $user)
    {
        return $user->hasPermission('people.helpers.manage');
    }

    /**
     * Determine whether the user can export helpers.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function export(User $user)
    {
        return $user->hasPermission('people.helpers.manage');
    }

        /**
     * Determine whether the user can import helpers.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function import(User $user)
    {
        return $user->hasPermission('people.helpers.manage');
    }

    /**
     * Determine whether the user can view the helper.
     *
     * @param  \App\User  $user
     * @param  \App\Helper  $helper
     * @return mixed
     */
    public function view(User $user, Helper $helper)
    {
        return $user->hasPermission('people.helpers.manage');
    }

    /**
     * Determine whether the user can create helpers.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return $user->hasPermission('people.helpers.manage');
    }

    /**
     * Determine whether the user can update the helper.
     *
     * @param  \App\User  $user
     * @param  \App\Helper  $helper
     * @return mixed
     */
    public function update(User $user, Helper $helper)
    {
        return $user->hasPermission('people.helpers.manage');
    }

    /**
     * Determine whether the user can delete the helper.
     *
     * @param  \App\User  $user
     * @param  \App\Helper  $helper
     * @return mixed
     */
    public function delete(User $user, Helper $helper)
    {
        return $user->hasPermission('people.helpers.manage');
    }

    /**
     * Determine whether the user can restore the helper.
     *
     * @param  \App\User  $user
     * @param  \App\Helper  $helper
     * @return mixed
     */
    public function restore(User $user, Helper $helper)
    {
        return $user->hasPermission('people.helpers.manage');
    }

    /**
     * Determine whether the user can permanently delete the helper.
     *
     * @param  \App\User  $user
     * @param  \App\Helper  $helper
     * @return mixed
     */
    public function forceDelete(User $user, Helper $helper)
    {
        return $user->hasPermission('people.helpers.manage');
    }
}
