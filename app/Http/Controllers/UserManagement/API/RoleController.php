<?php

namespace App\Http\Controllers\UserManagement\API;

use App\Http\Controllers\Controller;
use App\Http\Controllers\ValidatesResourceIndex;
use App\Http\Requests\UserManagement\StoreUpdateRole;
use App\Http\Resources\Role as RoleResource;
use App\Http\Resources\RoleCollection;
use App\Http\Resources\UserCollection;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;

class RoleController extends Controller
{
    use ValidatesResourceIndex;

    public function __construct()
    {
        $this->authorizeResource(Role::class);
    }

    public function index(): JsonResource
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

    public function store(StoreUpdateRole $request): JsonResponse
    {
        $role = new Role();
        $role->fill($request->validated());

        $role->save();

        Log::info('User role has been created.', [
            'role_id' => $role->id,
            'role_name' => $role->name,
            'client_ip' => $request->ip(),
        ]);

        return response()
            ->json([
                'message' => __('Role has been added.'),
            ], Response::HTTP_CREATED)
            ->header('Location', route('api.roles.show', $role));
    }

    public function show(Role $role): JsonResource
    {
        return new RoleResource($role);
    }

    public function update(StoreUpdateRole $request, Role $role): JsonResponse
    {
        $role->fill($request->validated());

        $role->save();

        Log::info('User role has been updated.', [
            'role_id' => $role->id,
            'role_name' => $role->name,
            'client_ip' => $request->ip(),
        ]);

        return response()
            ->json([
                'message' => __('Role has been updated.'),
            ]);
    }

    public function destroy(Request $request, Role $role): JsonResponse
    {
        $role->delete();

        Log::info('User role has been deleted.', [
            'role_id' => $role->id,
            'role_name' => $role->name,
            'client_ip' => $request->ip(),
        ]);

        return response()
            ->json([
                'message' => __('Role has been deleted.'),
            ]);
    }

    public function users(Role $role): JsonResource
    {
        $this->authorize('view', $role);
        $this->authorize('viewAny', User::class);

        return new UserCollection($role->users()
            ->orderBy('name', 'asc')
            ->get());
    }

    public function administrators(Role $role): JsonResource
    {
        $this->authorize('view', $role);
        $this->authorize('viewAny', User::class);

        return new UserCollection($role->administrators()
            ->orderBy('name', 'asc')
            ->get());
    }
}
