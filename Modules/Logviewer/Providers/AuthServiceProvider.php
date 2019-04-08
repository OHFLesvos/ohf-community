<?php

namespace Modules\Logviewer\Providers;

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
        'app.logs.view' => [
            'label' => 'logviewer::permissions.view_logs',
            'sensitive' => true,
        ],
    ];

    protected $permission_gate_mappings = [
        'view-logs' => 'app.logs.view',
    ];

}
