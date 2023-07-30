<?php

namespace App\Policies;

use App\Models\Role;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class RolePolicy
{
    use HandlesAuthorization;

    public function before($user, $ability)
    {
        if ($user->isSuperAdmin()) {
            return true;
        }
    }

    /**
     * Determine whether the user can list models.
     *
     * @return mixed
     */
    public function viewAny(User $user)
    {
        return $user->hasPermission('app.usermgmt.view');
    }

    /**
     * Determine whether the user can view the role.
     *
     * @return mixed
     */
    public function view(User $user, Role $role)
    {
        return $user->hasPermission('app.usermgmt.view');
    }

    /**
     * Determine whether the user can create roles.
     *
     * @return mixed
     */
    public function create(User $user)
    {
        return $user->hasPermission('app.usermgmt.roles.manage');
    }

    /**
     * Determine whether the user can update the role.
     *
     * @return mixed
     */
    public function update(User $user, Role $role)
    {
        return $user->hasPermission('app.usermgmt.roles.manage');
    }

    /**
     * Determine whether the user can delete the role.
     *
     * @return mixed
     */
    public function delete(User $user, Role $role)
    {
        return $user->hasPermission('app.usermgmt.roles.manage');
    }

    /**
     * Determine whether the user can manage the members the role.
     *
     * @return mixed
     */
    public function manageMembers(User $user, Role $role)
    {
        return $role->hasAdministrator($user->id);
    }
}
