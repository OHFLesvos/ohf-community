<?php

namespace App\Policies\Accounting;

use App\Models\Accounting\Category;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class CategoryPolicy
{
    use HandlesAuthorization;

    public function before($user, $ability)
    {
        if ($user->isSuperAdmin() && ! in_array($ability, ['delete'])) {
            return true;
        }
    }

    public function viewAny(User $user)
    {
        return $user->hasPermission('accounting.configure');
    }

    public function view(User $user, Category $category)
    {
        return $user->hasPermission('accounting.configure');
    }

    public function create(User $user)
    {
        return $user->hasPermission('accounting.configure');
    }

    public function update(User $user, Category $category)
    {
        return $user->hasPermission('accounting.configure');
    }

    public function delete(User $user, Category $category)
    {
        if ($user->isSuperAdmin() || $user->hasPermission('accounting.configure')) {
            return ! $category->transactions()->exists();
        }

        return false;
    }
}
