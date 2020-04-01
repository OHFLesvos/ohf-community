<?php

namespace App\Policies;

use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
{
    use HandlesAuthorization;

    public function before($user, $ability)
    {
        if ($user->isSuperAdmin() && $ability != 'delete') {
            return true;
        }
    }

    /**
     * Determine whether the user can list models.
     *
     * @param  \App\User  $user
     *
     * @return mixed
     */
    public function list(User $user)
    {
        return $user->hasPermission('app.usermgmt.view');
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\User  $user
     * @param  \App\User  $model
     *
     * @return mixed
     */
    public function view(User $user, User $model)
    {
        return $user->hasPermission('app.usermgmt.view');
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\User  $user
     *
     * @return mixed
     */
    public function create(User $user)
    {
        return $user->hasPermission('app.usermgmt.users.manage');
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\User  $user
     * @param  \App\User  $model
     *
     * @return mixed
     */
    public function update(User $user, User $model)
    {
        return $user->hasPermission('app.usermgmt.users.manage');
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\User  $user
     * @param  \App\User  $model
     *
     * @return mixed
     */
    public function delete(User $user, User $model)
    {
        // Ensure user cannot delete himself
        if ($user->id != $model->id) {
            // Permission check
            if ($user->isSuperAdmin() || $user->hasPermission('app.usermgmt.users.manage')) {
                // Ensure model user is not admin or not the only admin
                return ! $model->isSuperAdmin() || User::where('is_super_admin', true)->count() > 1;
            }
        }
        return false;
    }
}
