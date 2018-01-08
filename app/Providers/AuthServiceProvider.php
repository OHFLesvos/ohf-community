<?php

namespace App\Providers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        'App\User' => 'App\Policies\UserPolicy',
        'App\Role' => 'App\Policies\RolePolicy',
        'App\Person' => 'App\Policies\PersonPolicy',
        'App\Task' => 'App\Policies\TaskPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        $simple_permission_gate_mappings = [
            'view-bank-index' => ['bank.withdrawals.do', 'bank.deposits.do', 'bank.statistics.view', 'bank.configure'],
            'do-bank-withdrawals' => 'bank.withdrawals.do',
            'do-bank-deposits' => 'bank.deposits.do',
            'view-bank-statistics' => 'bank.statistics.view',
            'configure-bank' => 'bank.configure',
            'use-logistics' => 'logistics.use',
        ];
        foreach ($simple_permission_gate_mappings as $gate => $permission) {
            Gate::define($gate, function ($user) use($permission) {
                if ($user->isSuperAdmin()) {
                    return true;
                }
                if (is_array($permission)) {
                    $hasPermission = false;
                    foreach ($permission as $pe) {
                        $hasPermission |= $user->hasPermission($pe);
                    }
                    return $hasPermission;
                }
                return $user->hasPermission($permission);
            });
        }

    }
}
