<?php

namespace App\Policies\Collaboration;

use App\Models\Collaboration\CalendarEvent;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class CalendarEventPolicy
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
    public function viewAny(User $user)
    {
        return $user->hasPermission('calendar.events.view');
    }

    /**
     * Determine whether the user can view the calendarEvent.
     *
     * @param  \App\User  $user
     * @param  \App\Models\Collaboration\CalendarEvent  $calendarEvent
     * @return mixed
     */
    public function view(User $user, CalendarEvent $calendarEvent)
    {
        return $user->hasPermission('calendar.events.view');
    }

    /**
     * Determine whether the user can create calendarEvents.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return $user->hasPermission('calendar.events.create') || $user->hasPermission('calendar.events.manage');
    }

    /**
     * Determine whether the user can update the calendarEvent.
     *
     * @param  \App\User  $user
     * @param  \App\Models\Collaboration\CalendarEvent  $calendarEvent
     * @return mixed
     */
    public function update(User $user, CalendarEvent $calendarEvent)
    {
        return ($calendarEvent->user->id == $user->id && $user->hasPermission('calendar.events.create')) || $user->hasPermission('calendar.events.manage');
    }

    /**
     * Determine whether the user can delete the calendarEvent.
     *
     * @param  \App\User  $user
     * @param  \App\Models\Collaboration\CalendarEvent  $calendarEvent
     * @return mixed
     */
    public function delete(User $user, CalendarEvent $calendarEvent)
    {
        return ($calendarEvent->user->id == $user->id && $user->hasPermission('calendar.events.create')) || $user->hasPermission('calendar.events.manage');
    }
}
