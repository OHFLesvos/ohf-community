<?php

namespace App\Policies\Library;

use App\Models\Library\LibraryLending;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class LibraryLendingPolicy
{
    use HandlesAuthorization;

    public function before($user, $ability)
    {
        if ($user->isSuperAdmin()) {
            return true;
        }
    }

    /**
     * Determine whether the user can list library lendings.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function viewAny(User $user)
    {
        return $user->hasPermission('library.operate');
    }

    /**
     * Determine whether the user can view the library lending.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Library\LibraryLending  $libraryLending
     * @return mixed
     */
    public function view(User $user, LibraryLending $libraryLending)
    {
        return $user->hasPermission('library.operate');
    }

    /**
     * Determine whether the user can create library lendings.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return $user->hasPermission('library.operate');
    }

    /**
     * Determine whether the user can update the library lending.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Library\LibraryLending  $libraryLending
     * @return mixed
     */
    public function update(User $user, LibraryLending $libraryLending)
    {
        return $user->hasPermission('library.operate');
    }

    /**
     * Determine whether the user can delete the library lending.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Library\LibraryLending  $libraryLending
     * @return mixed
     */
    public function delete(User $user, LibraryLending $libraryLending)
    {
        return $user->hasPermission('library.operate');
    }

}
