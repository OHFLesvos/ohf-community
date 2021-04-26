<?php

namespace App\Http\Controllers\UserManagement;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserManagement\StoreRole;
use App\Http\Requests\UserManagement\UpdateMembers;
use App\Models\Role;
use App\Models\RolePermission;
use App\Models\User;

class RoleController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Role::class);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('user_management.roles.index', [
            'roles' => Role::with(['users', 'permissions'])
                ->orderBy('name')
                ->paginate(100),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('user_management.roles.create', [
            'users' => User::orderBy('name')
                ->get()
                ->pluck('name', 'id')
                ->toArray(),
            'permissions' => getCategorizedPermissions(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\UserManagement\StoreRole  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreRole $request)
    {
        $role = new Role();
        $role->name = $request->name;
        $role->save();
        $role->users()->sync($request->users);
        $role->administrators()->sync($request->role_admins);

        $requested_keys = collect($request->input('permissions', []));
        if ($requested_keys->isNotEmpty()) {
            $selected_permissions = $requested_keys
                ->map(fn ($key) => (new RolePermission())->withKey($key));
            $role->permissions()->saveMany($selected_permissions);
        }

        return redirect()
            ->route('roles.index')
            ->with('success', __('Role has been added.'));
    }

    /**
     * Display the specified resource.
     *
     * @param  Role $role
     * @return \Illuminate\Http\Response
     */
    public function show(Role $role)
    {
        $current_permissions = $role->permissions->pluck('key');
        $permissions = [];
        foreach (getCategorizedPermissions() as $title => $elements) {
            foreach ($elements as $key => $label) {
                if ($current_permissions->contains($key)) {
                    $permissions[$title][] = $label;
                }
            }
        }

        return view('user_management.roles.show', [
            'role' => $role,
            'permissions' => $permissions,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  Role $role
     * @return \Illuminate\Http\Response
     */
    public function edit(Role $role)
    {
        return view('user_management.roles.edit', [
            'role' => $role,
            'users' => User::orderBy('name')
                ->get()
                ->pluck('name', 'id')
                ->toArray(),
            'permissions' => getCategorizedPermissions(),
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \App\Http\Requests\UserManagement\StoreRole $request
     * @param  Role $role
     * @return \Illuminate\Http\Response
     */
    public function update(StoreRole $request, Role $role)
    {
        $role->name = $request->name;
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

        return redirect()
            ->route('roles.show', $role)
            ->with('success', __('Role has been updated.'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Role $role
     * @return \Illuminate\Http\Response
     */
    public function destroy(Role $role)
    {
        $role->delete();

        return redirect()
            ->route('roles.index')
            ->with('success', __('Role has been deleted.'));
    }

    /**
     * Lists all permissions
     */
    public function permissions()
    {
        $this->authorize('viewAny', Role::class);

        return view('user_management.roles.list-permissions', [
            'permissions' => getCategorizedPermissions(),
        ]);
    }

    /**
     * Show the form for managing the members the specified role.
     *
     * @param  Role $role
     * @return \Illuminate\Http\Response
     */
    public function manageMembers(Role $role)
    {
        $this->authorize('manageMembers', $role);

        return view('user_management.roles.manage-members', [
            'role' => $role,
            'users' => User::orderBy('name')
                ->get()
                ->pluck('name', 'id')
                ->toArray(),
        ]);
    }

    /**
     * Update the members of the specified role.
     *
     * @param \App\Http\Requests\UserManagement\UpdateMembers $request
     * @param  Role $role
     * @return \Illuminate\Http\Response
     */
    public function updateMembers(UpdateMembers $request, Role $role)
    {
        $this->authorize('manageMembers', $role);

        $role->users()->sync($request->users);

        return redirect()
            ->route('roles.show', $role)
            ->with('success', __('Role has been updated.'));
    }
}
