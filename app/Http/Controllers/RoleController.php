<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreRole;
use App\Role;
use App\RolePermission;
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
            'roles' => Role::with(['users', 'permissions'])->orderBy('name')->paginate()
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
            'permissions' => Config::get('auth.permissions')
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreRole $request)
    {
        $role = new Role();
        $role->name = $request->name;
        $role->save();

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
        return view('roles.show', [
            'role' => $role,
            'permissions' => Config::get('auth.permissions')
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
            'permissions' => Config::get('auth.permissions')
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param StoreRole|\Illuminate\Http\Request $request
     * @param  Role $role
     * @return \Illuminate\Http\Response
     */
    public function update(StoreRole $request, Role $role)
    {
        $role->name = $request->name;
        $role->save();

        if (isset($request->permissions)) {
            foreach ($request->permissions as $k) {
                if (!$role->permissions->contains(function ($value, $key) use ($k) { return $value->key == $k; })) {
                    $permission = new RolePermission();
                    $permission->key = $k;
                    $role->permissions()->save($permission);
                }
            }
        }
        foreach (Config::get('auth.permissions') as $k => $v) {
            if (!in_array($k, isset($request->permissions) ? $request->permissions : [])) {
                RolePermission::where('key', $k)->where('role_id', $role->id)->delete();
            }
        }

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
            'permissions' => Config::get('auth.permissions')
        ]);
    }
}
