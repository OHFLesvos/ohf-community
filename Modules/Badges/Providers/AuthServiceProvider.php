<?php

namespace Modules\Badges\Providers;

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
        'badges.create' => [
            'label' => 'badges::permissions.create_badges',
            'sensitive' => false,
        ],
    ];

    protected $permission_gate_mappings = [
        'create-badges' => 'badges.create',
    ];

}
