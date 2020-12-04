<?php

namespace App\Policies\Visitors;

use App\Models\Visitors\Visitor;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class VisitorPolicy
{
    use HandlesAuthorization;

    public function before($user)
    {
        if ($user->isSuperAdmin()) {
            return true;
        }
    }

    /**
     * Determine whether the user can view any models.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function viewAny(User $user)
    {
        return $user->hasPermission('visitors.register');
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Visitors\Visitor  $visitor
     * @return mixed
     */
    public function view(User $user, Visitor $visitor)
    {
        return $user->hasPermission('visitors.register');
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return $user->hasPermission('visitors.register');
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Visitors\Visitor  $visitor
     * @return mixed
     */
    public function update(User $user, Visitor $visitor)
    {
        return $user->hasPermission('visitors.register');
    }

    /**
     * Determine whether the user can update any model.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function updateAny(User $user)
    {
        return $user->hasPermission('visitors.register');
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Visitors\Visitor  $visitor
     * @return mixed
     */
    public function delete(User $user, Visitor $visitor)
    {
        return $user->hasPermission('visitors.register');
    }
}
