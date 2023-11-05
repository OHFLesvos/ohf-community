<?php

namespace App\Http\Controllers\UserManagement\API;

use App\Http\Controllers\Controller;
use App\Models\RolePermission;
use App\Models\User;
use Illuminate\Http\JsonResponse;

class UserReportController extends Controller
{
    public function userPermissions(): JsonResponse
    {
        $this->authorize('viewAny', User::class);

        $permissions = getCategorizedPermissions();
        $users = [];

        foreach($permissions as $elements) {
            foreach(array_keys($elements) as $key) {
                $roles = RolePermission::where('key', $key)
                    ->get()
                    ->map(fn ($e) => $e->role)
                    ->sortBy('name');
                $users[$key] = $roles->flatMap(fn ($e) => $e->users)
                    ->concat(User::where('is_super_admin', true)->get())
                    ->unique('id')
                    ->sortBy('name')
                    ->map(fn (User $user) => [
                        'id' => $user->id,
                        'name' => $user->name,
                        'email' => $user->email,
                        'is_super_admin' => $user->is_super_admin,
                    ]);
            }
        }

        return response()->json([
            'permissions' => $permissions,
            'users' => $users,
        ]);
    }
}
