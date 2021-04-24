<?php

namespace App\Policies\People;

use App\Models\People\Person;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class PersonPolicy
{
    use HandlesAuthorization;

    public function before($user, $ability)
    {
        if ($user->isSuperAdmin()) {
            return true;
        }
    }

    /**
     * Determine whether the user can list persons.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function viewAny(User $user)
    {
        return $user->hasPermission('people.list');
    }

    /**
     * Determine whether the user can export persons.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function export(User $user)
    {
        return $user->hasPermission('people.export');
    }

    /**
     * Determine whether the user can view the person.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\People\Person  $person
     * @return mixed
     */
    public function view(User $user, Person $person)
    {
        return $user->hasPermission('people.view');
    }

    /**
     * Determine whether the user can create persons.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return $user->hasPermission('people.manage');
    }

    /**
     * Determine whether the user can update the person.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\People\Person  $person
     * @return mixed
     */
    public function update(User $user, Person $person)
    {
        return $user->hasPermission('people.manage');
    }

    /**
     * Determine whether the user can delete the person.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\People\Person  $person
     * @return mixed
     */
    public function delete(User $user, Person $person)
    {
        return $user->hasPermission('people.manage');
    }

    /**
     * Determine whether the user can ckeanup persons.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function cleanup(User $user)
    {
        return $user->hasPermission('people.manage');
    }
}
