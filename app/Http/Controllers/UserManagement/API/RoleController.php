<?php

namespace App\Http\Controllers\UserManagement\API;

use App\Http\Controllers\Controller;
use App\Http\Controllers\ValidatesResourceIndex;
use App\Http\Requests\UserManagement\StoreRole;
use App\Http\Resources\Role as RoleResource;
use App\Http\Resources\RoleCollection;
use App\Http\Resources\UserCollection;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Response;

class RoleController extends Controller
{
    use ValidatesResourceIndex;

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
        $this->validateFilter();
        $this->validateSorting([
            'name',
            'created_at',
            'updated_at',
        ]);

        return new RoleCollection(Role::filtered($this->getFilter())
            ->when(in_array('users', $this->getIncludes()), fn ($qry) => $qry->with(['users' => fn ($qry) => $qry->orderBy('name')]))
            ->orderBy($this->getSortBy('name'), $this->getSortDirection())
            ->get());
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
        $role->fill($request->all());

        $role->save();

        return response()
            ->json([
                'message' => __('app.role_added'),
            ], Response::HTTP_CREATED)
            ->header('Location', route('api.roles.show', $role));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Role  $role
     * @return \Illuminate\Http\Response
     */
    public function show(Role $role)
    {
        return new RoleResource($role);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Role  $role
     * @return \Illuminate\Http\Response
     */
    public function update(StoreRole $request, Role $role)
    {
        $role->fill($request->all());

        $role->save();

        return response()
            ->json([
                'message' => __('app.role_updated'),
            ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Role  $role
     * @return \Illuminate\Http\Response
     */
    public function destroy(Role $role)
    {
        $role->delete();

        return response()
            ->json([
                'message' => __('app.role_deleted'),
            ]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function users(Role $role)
    {
        $this->authorize('view', $role);
        $this->authorize('viewAny', User::class);

        return new UserCollection($role->users()
            ->orderBy('name', 'asc')
            ->get());
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function administrators(Role $role)
    {
        $this->authorize('view', $role);
        $this->authorize('viewAny', User::class);

        return new UserCollection($role->administrators()
            ->orderBy('name', 'asc')
            ->get());
    }
}
