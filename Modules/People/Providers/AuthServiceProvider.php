<?php

namespace Modules\People\Providers;

use App\Providers\BaseAuthServiceProvider;

class AuthServiceProvider extends BaseAuthServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        \Modules\People\Entities\Person::class => \Modules\People\Policies\PersonPolicy::class,
    ];

    protected $permissions = [
        'people.manage' => [
            'label' => 'people::permissions.manage_people',
            'sensitive' => true,
        ],
        'people.export' => [
            'label' => 'people::permissions.export_people',
            'sensitive' => true,
        ],
        'people.reports.view' => [
            'label' => 'people::permissions.view_people_reports',
            'sensitive' => false,
        ],
    ];

    protected $permission_gate_mappings = [
        'manage-people' => 'people.manage',
        'view-people-reports' => 'people.reports.view',
    ];

}
