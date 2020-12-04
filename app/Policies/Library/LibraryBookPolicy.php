<?php

namespace App\Policies\Library;

use App\Models\Library\LibraryBook;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class LibraryBookPolicy
{
    use HandlesAuthorization;

    public function before($user, $ability)
    {
        if ($user->isSuperAdmin()) {
            return true;
        }
    }

    /**
     * Determine whether the user can list library books.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function viewAny(User $user)
    {
        return $user->hasPermission('library.operate');
    }

    /**
     * Determine whether the user can view the library book.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Library\LibraryBook  $libraryBook
     * @return mixed
     */
    public function view(User $user, LibraryBook $libraryBook)
    {
        return $user->hasPermission('library.operate');
    }

    /**
     * Determine whether the user can create library books.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return $user->hasPermission('library.operate');
    }

    /**
     * Determine whether the user can update the library book.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Library\LibraryBook  $libraryBook
     * @return mixed
     */
    public function update(User $user, LibraryBook $libraryBook)
    {
        return $user->hasPermission('library.operate');
    }

    /**
     * Determine whether the user can delete the library book.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Library\LibraryBook  $libraryBook
     * @return mixed
     */
    public function delete(User $user, LibraryBook $libraryBook)
    {
        return $user->hasPermission('library.operate');
    }

}
