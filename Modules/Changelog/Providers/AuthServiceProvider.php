<?php

namespace Modules\Changelog\Providers;

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
        'app.changelogs.view' => [
            'label' => 'changelog::permissions.view_changelogs',
            'sensitive' => false,
        ],
    ];

    protected $permission_gate_mappings = [
        'view-changelogs' => 'app.changelogs.view',
    ];

}
