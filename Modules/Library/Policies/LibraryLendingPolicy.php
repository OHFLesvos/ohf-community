<?php

namespace Modules\Library\Policies;

use App\User;

use Modules\Library\Entities\LibraryLending;

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
     * @param  \App\User  $user
     * @return mixed
     */
    public function list(User $user)
    {
        return $user->hasPermission('library.operate');
    }

    /**
     * Determine whether the user can view the library lending.
     *
     * @param  \App\User  $user
     * @param  \Modules\Library\Entities\LibraryLending  $libraryLending
     * @return mixed
     */
    public function view(User $user, LibraryLending $libraryLending)
    {
        return $user->hasPermission('library.operate');
    }

    /**
     * Determine whether the user can create library lendings.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return $user->hasPermission('library.operate');
    }

    /**
     * Determine whether the user can update the library lending.
     *
     * @param  \App\User  $user
     * @param  \Modules\Library\Entities\LibraryLending  $libraryLending
     * @return mixed
     */
    public function update(User $user, LibraryLending $libraryLending)
    {
        return $user->hasPermission('library.operate');
    }

    /**
     * Determine whether the user can delete the library lending.
     *
     * @param  \App\User  $user
     * @param  \Modules\Library\Entities\LibraryLending  $libraryLending
     * @return mixed
     */
    public function delete(User $user, LibraryLending $libraryLending)
    {
        return $user->hasPermission('library.operate');
    }

}
