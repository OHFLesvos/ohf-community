<?php

namespace Modules\Helpers\Providers;

use App\Providers\BaseAuthServiceProvider;

class AuthServiceProvider extends BaseAuthServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        \Modules\Helpers\Entities\Helper::class => \Modules\Helpers\Policies\HelperPolicy::class,
        \Modules\Helpers\Entities\Responsibility::class => \Modules\Helpers\Policies\ResponsibilityPolicy::class,
    ];

    protected $permissions = [
        'people.helpers.view' => [
            'label' => 'helpers::permissions.view_helpers',
            'sensitive' => true,
        ],
        'people.helpers.manage' => [
            'label' => 'helpers::permissions.manage_helpers',
            'sensitive' => true,
        ],
        'people.helpers.casework.view' => [
            'label' => 'helpers::permissions.view_helpers_casework',
            'sensitive' => true,
        ],
        'people.helpers.casework.manage' => [
            'label' => 'helpers::permissions.manage_helpers_casework',
            'sensitive' => true,
        ],
    ];

    protected $permission_gate_mappings = [
        'manage-helpers' => 'people.helpers.manage',
    ];
   
    protected $permission_gate_mappings_no_super_admin = [
        'view-helpers-casework' => 'people.helpers.casework.view',
        'manage-helpers-casework' => 'people.helpers.casework.manage',
    ];
}
