<?php

namespace App\Providers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

abstract class BaseAuthServiceProvider extends ServiceProvider
{
    protected $permission_gate_mappings = [];
    protected $permission_gate_mappings_no_super_admin = [];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();
        $this->registerPermissionGateMappings();
        $this->registerPErmissionGateMappingsNoSuperAdmin();
    }

    protected function registerPermissionGateMappings() {
        foreach ($this->permission_gate_mappings as $gate => $permission) {
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

    protected function registerPErmissionGateMappingsNoSuperAdmin() {
        foreach ($this->permission_gate_mappings_no_super_admin as $gate => $permission) {
            Gate::define($gate, function ($user) use($permission) {
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
