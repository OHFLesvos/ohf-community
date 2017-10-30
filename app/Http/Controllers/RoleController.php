<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreRole;
use App\Role;
use App\RolePermission;
use Illuminate\Support\Facades\Config;

class RoleController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->authorize('list', Role::class);

        return view('roles.index', [
            'roles' => Role::orderBy('name')
                ->paginate()
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->authorize('create', Role::class);

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
        $this->authorize('create', Role::class);

        $role = new Role();
        $role->name = $request->name;
        $role->save();

        if (isset($request->permissions)) {
            foreach ($request->permissions as $k) {
                $p = new RolePermission();
                $p->key = $k;
                $p->role_id = $role->id;
                $p->save();
            }
        }

        return redirect()->route('roles.index')
            ->with('success', 'Role has been added.');
    }

    /**
     * Display the specified resource.
     *
     * @param  Role $role
     * @return \Illuminate\Http\Response
     */
    public function show(Role $role)
    {
        $this->authorize('view', $role);

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
        $this->authorize('update', $role);

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
        $this->authorize('update', $role);

        $role->name = $request->name;
        $role->save();

        if (isset($request->permissions)) {
            foreach ($request->permissions as $k) {
                if (!$role->permissions->contains(function ($value, $key) use ($k) { return $value->key == $k; })) {
                    $p = new RolePermission();
                    $p->key = $k;
                    $p->role_id = $role->id;
                    $p->save();
                }
            }
        }
        foreach (Config::get('auth.permissions') as $k => $v) {
            if (!in_array($k, isset($request->permissions) ? $request->permissions : [])) {
                RolePermission::where('key', $k)->where('role_id', $role->id)->delete();
            }
        }

        return redirect()->route('roles.show', $role)
            ->with('success', 'Role has been updated.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Role $role
     * @return \Illuminate\Http\Response
     */
    public function destroy(Role $role)
    {
        $this->authorize('delete', $role);

        $role->delete();
        return redirect()->route('roles.index')
            ->with('success', 'Role has been deleted.');
    }
}
