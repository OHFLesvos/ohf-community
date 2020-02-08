<?php

namespace Modules\Collaboration\Policies;

use App\User;

use Modules\Collaboration\Entities\CalendarResource;

use Illuminate\Auth\Access\HandlesAuthorization;

class ResourcePolicy
{
    use HandlesAuthorization;

    public function before($user, $ability)
    {
        if ($user->isSuperAdmin()) {
            return true;
        }
    }

    /**
     * Determine whether the user can list calendar events.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function list(User $user)
    {
        return $user->hasPermission('calendar.events.view');
    }

    /**
     * Determine whether the user can view the calendarResource.
     *
     * @param  \App\User  $user
     * @param  \Modules\Collaboration\Entities\CalendarResource  $calendarResource
     * @return mixed
     */
    public function view(User $user, CalendarResource $calendarResource)
    {
        return $user->hasPermission('calendar.events.view');
    }

    /**
     * Determine whether the user can create calendarResources.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return $user->hasPermission('calendar.resources.manage');
    }

    /**
     * Determine whether the user can update the calendarResource.
     *
     * @param  \App\User  $user
     * @param  \Modules\Collaboration\Entities\CalendarResource  $calendarResource
     * @return mixed
     */
    public function update(User $user, CalendarResource $calendarResource)
    {
        return $user->hasPermission('calendar.resources.manage');
    }

    /**
     * Determine whether the user can delete the calendarResource.
     *
     * @param  \App\User  $user
     * @param  \Modules\Collaboration\Entities\CalendarResource  $calendarResource
     * @return mixed
     */
    public function delete(User $user, CalendarResource $calendarResource)
    {
        return $user->hasPermission('calendar.resources.manage');
    }
}
