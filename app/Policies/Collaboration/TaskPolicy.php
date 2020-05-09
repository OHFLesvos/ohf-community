<?php

namespace App\Policies\Collaboration;

use App\Models\Collaboration\Task;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class TaskPolicy
{
    use HandlesAuthorization;

    public function before($user, $ability)
    {
        if ($user->isSuperAdmin()) {
            return true;
        }
    }

    /**
     * Determine whether the user can list tasks.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function viewAny(User $user)
    {
        return $user->hasPermission('tasks.use');
    }

    /**
     * Determine whether the user can view the task.
     *
     * @param  \App\User  $user
     * @param  \App\Models\Collaboration\Task  $task
     * @return mixed
     */
    public function view(User $user, Task $task)
    {
        return $user->hasPermission('tasks.use') && $task->user->id == $user->id;
    }

    /**
     * Determine whether the user can create tasks.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return $user->hasPermission('tasks.use');
    }

    /**
     * Determine whether the user can update the task.
     *
     * @param  \App\User  $user
     * @param  \App\Models\Collaboration\Task  $task
     * @return mixed
     */
    public function update(User $user, Task $task)
    {
        return $user->hasPermission('tasks.use') && $task->user->id == $user->id;
    }

    /**
     * Determine whether the user can delete the task.
     *
     * @param  \App\User  $user
     * @param  \App\Models\Collaboration\Task  $task
     * @return mixed
     */
    public function delete(User $user, Task $task)
    {
        return $user->hasPermission('tasks.use') && $task->user->id == $user->id;
    }
}
