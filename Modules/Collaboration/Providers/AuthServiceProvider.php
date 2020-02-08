<?php

namespace Modules\Collaboration\Providers;

use App\Providers\BaseAuthServiceProvider;

class AuthServiceProvider extends BaseAuthServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        \Modules\Collaboration\Entities\CalendarEvent::class    => \Modules\Collaboration\Policies\CalendarEventPolicy::class,
        \Modules\Collaboration\Entities\CalendarResource::class => \Modules\Collaboration\Policies\ResourcePolicy::class,
    ];

    protected $permissions = [
        'calendar.events.view' => [
            'label' => 'collaboration::permissions.view_calendar_events',
            'sensitive' => false,
        ],
        'calendar.events.create' => [
            'label' => 'collaboration::permissions.create_calendar_events',
            'sensitive' => false,
        ],
        'calendar.events.manage' => [
            'label' => 'collaboration::permissions.manage_calendar_events',
            'sensitive' => false,
        ],
        'calendar.resources.manage' => [
            'label' => 'collaboration::permissions.manage_calendar_resources',
            'sensitive' => false,
        ],
    ];

    protected $permission_gate_mappings = [
        'view-calendar' => 'calendar.events.view',
    ];

}
