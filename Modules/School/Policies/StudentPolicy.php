<?php

namespace Modules\School\Policies;

use App\User;

use Modules\School\Entities\Student;

use Illuminate\Auth\Access\HandlesAuthorization;

class StudentPolicy
{
    use HandlesAuthorization;

    public function before($user, $ability)
    {
        if ($user->isSuperAdmin()) {
            return true;
        }
    }

    /**
     * Determine whether the user can list students.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function list(User $user)
    {
        return $user->hasPermission('school.students.view');
    }

    /**
     * Determine whether the user can view the student.
     *
     * @param  \App\User  $user
     * @param  \Modules\Calendar\Entities\Student  $student
     * @return mixed
     */
    public function view(User $user, Student $student)
    {
        return $user->hasPermission('school.students.view');
    }

    /**
     * Determine whether the user can create students.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return $user->hasPermission('school.students.manage');
    }

    /**
     * Determine whether the user can update the student.
     *
     * @param  \App\User  $user
     * @param  \Modules\Calendar\Entities\Student  $student
     * @return mixed
     */
    public function update(User $user, Student $student)
    {
        return $user->hasPermission('school.students.manage');
    }

    /**
     * Determine whether the user can delete the student.
     *
     * @param  \App\User  $user
     * @param  \Modules\Calendar\Entities\Student  $student
     * @return mixed
     */
    public function delete(User $user, Student $student)
    {
        return $user->hasPermission('school.students.manage');
    }
}
