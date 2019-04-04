<?php

namespace App\Providers;

class AuthServiceProvider extends BaseAuthServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        \App\User::class => \App\Policies\UserPolicy::class,
        \App\Role::class => \App\Policies\RolePolicy::class,
        \App\Person::class => \App\Policies\PersonPolicy::class,
        \App\CouponType::class => \App\Policies\People\Bank\CouponTypePolicy::class,
    ];

    protected $permissions = [
        'people.manage' => [
            'label' => 'permissions.manage_people',
            'sensitive' => true,
        ],
        'people.export' => [
            'label' => 'permissions.export_people',
            'sensitive' => true,
        ],
        'people.reports.view' => [
            'label' => 'permissions.view_people_reports',
            'sensitive' => false,
        ],
    ];

    protected $permission_gate_mappings = [
        'manage-people' => 'people.manage',
        'view-people-reports' => 'people.reports.view',
        'view-reports' => ['people.reports.view', 'bank.statistics.view', 'app.usermgmt.view'],
    ];

}
