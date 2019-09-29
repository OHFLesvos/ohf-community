<?php

namespace Modules\People\Policies;

use App\User;

use Modules\People\Entities\Person;

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
     * @param  \App\User  $user
     * @return mixed
     */
    public function list(User $user)
    {
        return $user->hasPermission('people.list')
            || $user->hasPermission('library.operate');
    }

    /**
     * Determine whether the user can export persons.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function export(User $user)
    {
        return $user->hasPermission('people.export');
    }

    /**
     * Determine whether the user can view the person.
     *
     * @param  \App\User  $user
     * @param  \Modules\People\Entities\Person  $person
     * @return mixed
     */
    public function view(User $user, Person $person)
    {
        return $user->hasPermission('people.view');
    }

    /**
     * Determine whether the user can create persons.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return $user->hasPermission('people.manage') 
            || $user->hasPermission('library.operate');
    }

    /**
     * Determine whether the user can update the person.
     *
     * @param  \App\User  $user
     * @param  \Modules\People\Entities\Person  $person
     * @return mixed
     */
    public function update(User $user, Person $person)
    {
        return $user->hasPermission('people.manage');
    }

    /**
     * Determine whether the user can delete the person.
     *
     * @param  \App\User  $user
     * @param  \Modules\People\Entities\Person  $person
     * @return mixed
     */
    public function delete(User $user, Person $person)
    {
        return $user->hasPermission('people.manage');
    }

    /**
     * Determine whether the user can ckeanup persons.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function cleanup(User $user)
    {
        return $user->hasPermission('people.manage');
    }

}
