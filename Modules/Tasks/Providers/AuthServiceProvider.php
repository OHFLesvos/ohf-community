<?php

namespace Modules\Tasks\Providers;

use App\Providers\BaseAuthServiceProvider;

class AuthServiceProvider extends BaseAuthServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        \Modules\Tasks\Entities\Task::class => \Modules\Tasks\Policies\TaskPolicy::class,
    ];

    protected $permissions = [
        'tasks.use' => [
            'label' => 'tasks::permissions.use_tasks',
            'sensitive' => false,
        ],
    ];

    protected $permission_gate_mappings = [
    ];

}
