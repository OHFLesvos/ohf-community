<?php

namespace App\Policies\Library;

use App\User;
use App\LibraryBook;
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
     * @param  \App\User  $user
     * @return mixed
     */
    public function list(User $user)
    {
        return $user->hasPermission('library.operate');
    }

    /**
     * Determine whether the user can view the library book.
     *
     * @param  \App\User  $user
     * @param  \App\LibraryBook  $libraryBook
     * @return mixed
     */
    public function view(User $user, LibraryBook $libraryBook)
    {
        return $user->hasPermission('library.operate');
    }

    /**
     * Determine whether the user can create library books.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return $user->hasPermission('library.operate');
    }

    /**
     * Determine whether the user can update the library book.
     *
     * @param  \App\User  $user
     * @param  \App\LibraryBook  $libraryBook
     * @return mixed
     */
    public function update(User $user, LibraryBook $libraryBook)
    {
        return $user->hasPermission('library.operate');
    }

    /**
     * Determine whether the user can delete the library book.
     *
     * @param  \App\User  $user
     * @param  \App\LibraryBook  $libraryBook
     * @return mixed
     */
    public function delete(User $user, LibraryBook $libraryBook)
    {
        return $user->hasPermission('library.operate');
    }

}
