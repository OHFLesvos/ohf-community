<?php

namespace App\Policies;

use App\User;
use App\CalendarEvent;
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
    public function list(User $user)
    {
        return $user->hasPermission('calendar.events.view');
    }

    /**
     * Determine whether the user can view the calendarEvent.
     *
     * @param  \App\User  $user
     * @param  \App\CalendarEvent  $calendarEvent
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
        return $user->hasPermission('calendar.events.crud');
    }

    /**
     * Determine whether the user can update the calendarEvent.
     *
     * @param  \App\User  $user
     * @param  \App\CalendarEvent  $calendarEvent
     * @return mixed
     */
    public function update(User $user, CalendarEvent $calendarEvent)
    {
        return $user->hasPermission('calendar.events.crud'); // TODO && $calendarEvent->user->id == $user->id;
    }

    /**
     * Determine whether the user can delete the calendarEvent.
     *
     * @param  \App\User  $user
     * @param  \App\CalendarEvent  $calendarEvent
     * @return mixed
     */
    public function delete(User $user, CalendarEvent $calendarEvent)
    {
        return $user->hasPermission('calendar.events.crud'); // TODO && $calendarEvent->user->id == $user->id;
    }
}
