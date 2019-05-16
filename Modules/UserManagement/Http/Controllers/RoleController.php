<?php

namespace Modules\UserManagement\Http\Controllers;

use App\Role;
use App\User;
use App\RolePermission;
use App\Http\Controllers\Controller;
use App\Support\Facades\PermissionRegistry;

use Modules\UserManagement\Http\Requests\StoreRole;
use Modules\UserManagement\Http\Requests\UpdateMembers;

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
        return view('usermanagement::roles.index', [
            'roles' => Role::with(['users', 'permissions'])->orderBy('name')->paginate(100)
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('usermanagement::roles.create', [
            'users' => User::orderBy('name')->get()->pluck('name', 'id')->toArray(),
            'permissions' => PermissionRegistry::getCategorizedPermissions(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Modules\UserManagement\Http\Requests\StoreRole  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreRole $request)
    {
        $role = new Role();
        $role->name = $request->name;
        $role->save();
        $role->users()->sync($request->users);
        $role->administrators()->sync($request->role_admins);

        if (isset($request->permissions)) {
            foreach ($request->permissions as $k) {
                $permission = new RolePermission();
                $permission->key = $k;
                $role->permissions()->save($permission);
            }
        }

        return redirect()->route('roles.index')
            ->with('success', __('app.role_added'));
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
        foreach (PermissionRegistry::getCategorizedPermissions() as $title => $elements) {
            foreach($elements as $key => $label) {
                if ($current_permissions->contains($key)) {
                    $permissions[$title][] = $label;
                }
            }
        }

        return view('usermanagement::roles.show', [
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
        return view('usermanagement::roles.edit', [
            'role' => $role,
            'users' => User::orderBy('name')->get()->pluck('name', 'id')->toArray(),
            'permissions' => PermissionRegistry::getCategorizedPermissions(),
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Modules\UserManagement\Http\Requests\StoreRole $request
     * @param  Role $role
     * @return \Illuminate\Http\Response
     */
    public function update(StoreRole $request, Role $role)
    {
        $role->name = $request->name;
        $role->save();
        $role->users()->sync($request->users);
        $role->administrators()->sync($request->role_admins);

        if (isset($request->permissions)) {
            foreach ($request->permissions as $k) {
                if (!$role->permissions->contains(function ($value, $key) use ($k) { return $value->key == $k; })) {
                    $permission = new RolePermission();
                    $permission->key = $k;
                    $role->permissions()->save($permission);
                }
            }
        }
        foreach (PermissionRegistry::collection() as $k => $v) {
            if (!in_array($k, isset($request->permissions) ? $request->permissions : [])) {
                RolePermission::where('key', $k)->where('role_id', $role->id)->delete();
            }
        }
        $valid_keys = PermissionRegistry::collection()->keys();
        RolePermission::destroy($role->permissions->whereNotIn('key', $valid_keys)->pluck('id')->toArray());

        return redirect()->route('roles.show', $role)
            ->with('success', __('app.role_updated'));
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
        return redirect()->route('roles.index')
            ->with('success', __('app.role_deleted'));
    }

    /**
     * Lists all permissions
     */
    public function permissions()
    {
        return view('usermanagement::roles.permission_report', [
            'permissions' => PermissionRegistry::getCategorizedPermissions(),
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

        return view('usermanagement::roles.manageMembers', [
            'role' => $role,
            'users' => User::orderBy('name')->get()->pluck('name', 'id')->toArray(),
        ]);
    }

    /**
     * Update the members of the specified role.
     *
     * @param \Modules\UserManagement\Http\Requests\UpdateMembers $request
     * @param  Role $role
     * @return \Illuminate\Http\Response
     */
    public function updateMembers(UpdateMembers $request, Role $role)
    {
        $this->authorize('manageMembers', $role);

        $role->users()->sync($request->users);

        return redirect()->route('roles.show', $role)
            ->with('success', __('app.role_updated'));
    }

    
}
