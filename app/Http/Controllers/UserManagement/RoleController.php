<?php

namespace App\Http\Controllers\UserManagement;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserManagement\StoreUpdateRole;
use App\Http\Requests\UserManagement\UpdateMembers;
use App\Models\Role;
use App\Models\RolePermission;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;

class RoleController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Role::class);
    }

    public function create(): View
    {
        return view('user_management.roles.create', [
            'users' => User::orderBy('name')
                ->pluck('name', 'id')
                ->toArray(),
            'permissions' => getCategorizedPermissions(),
        ]);
    }

    public function store(StoreUpdateRole $request): RedirectResponse
    {
        $role = new Role();
        $role->fill($request->validated());
        $role->save();

        $role->users()->sync($request->users);
        $role->administrators()->sync($request->role_admins);

        $requested_keys = collect($request->input('permissions', []));
        if ($requested_keys->isNotEmpty()) {
            $selected_permissions = $requested_keys
                ->map(fn ($key) => (new RolePermission())->withKey($key));
            $role->permissions()->saveMany($selected_permissions);
        }

        Log::info('User role has been created.', [
            'role_id' => $role->id,
            'role_name' => $role->name,
            'client_ip' => $request->ip(),
        ]);

        return redirect()
            ->route('roles.index')
            ->with('success', __('Role has been added.'));
    }

    public function edit(Role $role): View
    {
        return view('user_management.roles.edit', [
            'role' => $role,
            'users' => User::orderBy('name')
                ->pluck('name', 'id')
                ->toArray(),
            'permissions' => getCategorizedPermissions(),
        ]);
    }

    public function update(StoreUpdateRole $request, Role $role): RedirectResponse
    {
        $role->fill($request->validated());
        $role->save();

        $role->users()->sync($request->users);
        $role->administrators()->sync($request->role_admins);

        // Save requested permissions
        $requested_keys = collect($request->input('permissions', []));
        if ($requested_keys->isNotEmpty()) {
            $selected_permissions = $requested_keys
                ->filter(fn ($key) => ! $role->permissions->contains('key', $key))
                ->map(fn ($key) => (new RolePermission())->withKey($key));
            $role->permissions()->saveMany($selected_permissions);
        }

        // Remove non-requested permissions
        $valid_keys = array_keys(config('permissions.keys'));
        $not_requested_keys = collect($valid_keys)
            ->filter(fn ($key) => ! $requested_keys->contains($key))
            ->toArray();
        RolePermission::whereIn('key', $not_requested_keys)
            ->where('role_id', $role->id)
            ->delete();

        // Remove invalid permissions
        $role->permissions()->whereNotIn('key', $valid_keys)
            ->delete();

        Log::info('User role has been updated.', [
            'role_id' => $role->id,
            'role_name' => $role->name,
            'client_ip' => $request->ip(),
        ]);

        return redirect()
            ->route('roles.show', $role)
            ->with('success', __('Role has been updated.'));
    }

    public function permissions(): View
    {
        $this->authorize('viewAny', Role::class);

        return view('user_management.roles.list-permissions', [
            'permissions' => getCategorizedPermissions(),
        ]);
    }

    public function manageMembers(Role $role): View
    {
        $this->authorize('manageMembers', $role);

        return view('user_management.roles.manage-members', [
            'role' => $role,
            'users' => User::orderBy('name')
                ->pluck('name', 'id')
                ->toArray(),
        ]);
    }

    public function updateMembers(UpdateMembers $request, Role $role): RedirectResponse
    {
        $this->authorize('manageMembers', $role);

        $role->users()->sync($request->users);

        return redirect()
            ->route('roles.show', $role)
            ->with('success', __('Role has been updated.'));
    }
}
