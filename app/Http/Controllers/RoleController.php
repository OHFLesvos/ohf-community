<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreRole;
use App\Permission;
use App\Role;

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
            'permissions' => Permission::orderBy('name')->get()
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
        $role->permissions()->sync($request->permissions);
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
            'role' => $role
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
            'permissions' => Permission::orderBy('name')->get()
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
        $role->permissions()->sync($request->permissions);
        if ($role->isDirty()) {
            $role->save();
            return redirect()->route('roles.show', $role)
                ->with('success', 'Role has been updated.');
        }
        return redirect()->route('roles.show', $role)
            ->with('info', 'No changes have been made.');
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
