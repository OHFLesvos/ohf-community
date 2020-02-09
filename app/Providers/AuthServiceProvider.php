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
        \App\Tag::class => \App\Policies\TagPolicy::class,
    ];

    protected $permissions = [
        'app.usermgmt.view' => [
            'label' => 'permissions.view_usermgmt',
            'sensitive' => true,
        ],
        'app.usermgmt.users.manage' => [
            'label' => 'permissions.usermgmt_manage_users',
            'sensitive' => true,
        ],
        'app.usermgmt.roles.manage' => [
            'label' => 'permissions.usermgmt_manage_roles',
            'sensitive' => false,
        ],
    ];

    protected $permission_gate_mappings = [
        'view-reports' => ['people.reports.view', 'bank.statistics.view', 'app.usermgmt.view'], // TODO
        'view-usermgmt-reports' => 'app.usermgmt.view',
    ];

}
