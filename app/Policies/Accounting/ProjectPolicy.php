<?php

namespace App\Policies\Accounting;

use App\Models\Accounting\Project;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ProjectPolicy
{
    use HandlesAuthorization;

    public function before($user, $ability)
    {
        if ($user->isSuperAdmin()) {
            return true;
        }
    }

    public function viewAny(User $user)
    {
        return $user->hasPermission('accounting.configure');
    }

    public function view(User $user, Project $project)
    {
        return $user->hasPermission('accounting.configure');
    }

    public function create(User $user)
    {
        return $user->hasPermission('accounting.configure');
    }

    public function update(User $user, Project $project)
    {
        return $user->hasPermission('accounting.configure');
    }

    public function delete(User $user, Project $project)
    {
        return $user->hasPermission('accounting.configure');
    }
}
