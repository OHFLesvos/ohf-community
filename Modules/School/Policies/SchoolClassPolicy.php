<?php

namespace Modules\School\Policies;

use App\User;

use Modules\School\Entities\SchoolClass;

use Illuminate\Auth\Access\HandlesAuthorization;

class SchoolClassPolicy
{
    use HandlesAuthorization;

    public function before($user, $ability)
    {
        if ($user->isSuperAdmin() && $ability != 'delete') {
            return true;
        }
    }

    /**
     * Determine whether the user can list school classes.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function list(User $user)
    {
        return $user->hasPermission('school.classes.view');
    }

    /**
     * Determine whether the user can view the school class.
     *
     * @param  \App\User  $user
     * @param  \Modules\Calendar\Entities\SchoolClass  $schoolClass
     * @return mixed
     */
    public function view(User $user, SchoolClass $schoolClass)
    {
        return $user->hasPermission('school.classes.view');
    }

    /**
     * Determine whether the user can create school classses.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return $user->hasPermission('school.classes.manage');
    }

    /**
     * Determine whether the user can update the school class.
     *
     * @param  \App\User  $user
     * @param  \Modules\Calendar\Entities\SchoolClass  $schoolClass
     * @return mixed
     */
    public function update(User $user, SchoolClass $schoolClass)
    {
        return $user->hasPermission('school.classes.manage');
    }

    /**
     * Determine whether the user can delete the school class.
     *
     * @param  \App\User  $user
     * @param  \Modules\Calendar\Entities\SchoolClass  $schoolClass
     * @return mixed
     */
    public function delete(User $user, SchoolClass $schoolClass)
    {
        if ($schoolClass->students()->count() == 0) {
            return $user->isSuperAdmin() || $user->hasPermission('school.classes.manage');
        }
        return false;
    }
}
