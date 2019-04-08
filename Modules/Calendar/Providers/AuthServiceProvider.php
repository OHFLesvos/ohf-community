<?php

namespace Modules\Calendar\Providers;

use App\Providers\BaseAuthServiceProvider;

class AuthServiceProvider extends BaseAuthServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [       
        \Modules\Calendar\Entities\CalendarEvent::class    => \Modules\Calendar\Policies\CalendarEventPolicy::class,
        \Modules\Calendar\Entities\CalendarResource::class => \Modules\Calendar\Policies\ResourcePolicy::class,
    ];

    protected $permissions = [
        'calendar.events.view' => [
            'label' => 'calendar::permissions.view_calendar_events',
            'sensitive' => false,
        ],
        'calendar.events.create' => [
            'label' => 'calendar::permissions.create_calendar_events',
            'sensitive' => false,
        ],
        'calendar.events.manage' => [
            'label' => 'calendar::permissions.manage_calendar_events',
            'sensitive' => false,
        ],
        'calendar.resources.manage' => [
            'label' => 'calendar::permissions.manage_calendar_resources',
            'sensitive' => false,
        ],
    ];

    protected $permission_gate_mappings = [
        'view-calendar' => 'calendar.events.view',
    ];

}
