<?php

namespace Modules\UserManagement\Providers;

use App\Providers\BaseAuthServiceProvider;

class AuthServiceProvider extends BaseAuthServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
    ];

    protected $permissions = [
        'app.usermgmt.view' => [
            'label' => 'usermanagement::permissions.view_usermgmt',
            'sensitive' => true,
        ],
        'app.usermgmt.users.manage' => [
            'label' => 'usermanagement::permissions.usermgmt_manage_users',
            'sensitive' => true,
        ],
        'app.usermgmt.roles.manage' => [
            'label' => 'usermanagement::permissions.usermgmt_manage_roles',
            'sensitive' => false,
        ],
    ];

    protected $permission_gate_mappings = [
        'view-usermgmt-reports' => 'app.usermgmt.view',
    ];

}
