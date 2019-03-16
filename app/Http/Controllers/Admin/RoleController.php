<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\ParentController;
use App\Http\Requests\Admin\StoreRole;
use App\Role;
use App\User;
use App\RolePermission;
use App\Support\Facades\PermissionRegistry;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;

class RoleController extends ParentController
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->authorizeResource(Role::class);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('roles.index', [
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
        return view('roles.create', [
            'users' => User::orderBy('name')->get()->pluck('name', 'id')->toArray(),
            'permissions' => self::getCategorizedPermissions(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\Admin\StoreRole  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreRole $request)
    {
        $role = new Role();
        $role->name = $request->name;
        $role->save();
        $role->users()->sync($request->users);

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
        foreach (self::getCategorizedPermissions() as $title => $elements) {
            foreach($elements as $key => $label) {
                if ($current_permissions->contains($key)) {
                    $permissions[$title][] = $label;
                }
            }
        }

        return view('roles.show', [
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
        return view('roles.edit', [
            'role' => $role,
            'users' => User::orderBy('name')->get()->pluck('name', 'id')->toArray(),
            'permissions' => self::getCategorizedPermissions(),
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \App\Http\Requests\Admin\StoreRole $request
     * @param  Role $role
     * @return \Illuminate\Http\Response
     */
    public function update(StoreRole $request, Role $role)
    {
        $role->name = $request->name;
        $role->save();
        $role->users()->sync($request->users);

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
        return view('roles.permissions', [
            'permissions' => self::getCategorizedPermissions(),
        ]);
    }

    public static function getCategorizedPermissions() {
        $map = PermissionRegistry::collection()
            ->toArray();
        $permissions = [];
        foreach($map as $k => $v) {
            if (preg_match('/^(.+): (.+)$/', $v, $m)) {
                $permissions[$m[1]][$k] = $m[2];
            } else {
                $permissions[null][$k] = $v;
            }
        }
        ksort($permissions);
        return $permissions;
    }
}
