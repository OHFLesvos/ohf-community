<?php

namespace App\Http\Controllers\UserManagement\API;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\RolePermission;
use Illuminate\Http\JsonResponse;

class RoleReportController extends Controller
{
    public function rolePermissions(): JsonResponse
    {
        $this->authorize('viewAny', Role::class);

        $permissions = getCategorizedPermissions();
        $roles = [];

        foreach ($permissions as $elements) {
            foreach (array_keys($elements) as $key) {
                $roles[$key] = RolePermission::where('key', $key)
                    ->get()
                    ->map(fn ($e) => $e->role)
                    ->sortBy('name')
                    ->map(fn (Role $role) => [
                        'id' => $role->id,
                        'name' => $role->name,
                    ]);
            }
        }

        return response()->json([
            'permissions' => $permissions,
            'roles' => $roles,
        ]);
    }
}
