<?php

namespace App\Http\Controllers\UserManagement\API;

use App\Http\Controllers\Controller;
use App\Http\Controllers\ValidatesResourceIndex;
use App\Http\Requests\UserManagement\StoreUpdateRole;
use App\Http\Resources\Role as RoleResource;
use App\Http\Resources\User as UserResource;
use App\Models\Role;
use App\Models\RolePermission;
use App\Models\User;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
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

        return RoleResource::collection(Role::query()
            ->when($this->getFilter() !== null,
                fn (Builder $qry) => $this->filterQuery($qry, $this->getFilter()))
            ->when(in_array('users', $this->getIncludes()),
                fn (Builder $qry) => $qry->with([
                    'users' => fn (BelongsToMany $innerQuery) => $innerQuery->orderBy('name'),
                ]))
            ->orderBy($this->getSortBy('name'), $this->getSortDirection())
            ->get());
    }

    private function filterQuery(Builder $query, string $filter): Builder
    {
        return $query->where('name', 'LIKE', '%'.$filter.'%');
    }

    public function store(StoreUpdateRole $request): JsonResponse
    {
        $role = new Role();
        $role->fill($request->validated());

        $role->save();

        $role->users()->sync($request->users);
        $role->administrators()->sync($request->administrators);

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

        return response()
            ->json([
                'message' => __('Role has been added.'),
                'id' => $role->id,
            ], Response::HTTP_CREATED)
            ->header('Location', route('api.roles.show', $role));
    }

    public function show(Role $role): JsonResource
    {
        if (in_array('users', $this->getIncludes())) {
            $role->load(['users' => fn ($q) => $q->orderBy('name')]);
        }
        if (in_array('administrators', $this->getIncludes())) {
            $role->load(['administrators' => fn ($q) => $q->orderBy('name')]);
        }

        $current_permissions = $role->permissions->pluck('key');
        $permissions = [];
        foreach (getCategorizedPermissions() as $title => $elements) {
            foreach ($elements as $key => $label) {
                if ($current_permissions->contains($key)) {
                    $permissions[$title][$key] = $label;
                }
            }
        }

        return (new RoleResource($role))->additional([
            'permissions' => $permissions,
            'users' => $role->users()->orderBy('name')->select('users.name', 'users.id')->get()->map(fn ($u) => [
                'id' => $u->id,
                'name' => $u->name,
            ]),
            'administrators' => $role->administrators()->orderBy('name')->select('users.name', 'users.id')->get()->map(fn ($u) => [
                'id' => $u->id,
                'name' => $u->name,
            ]),
            'is_administrator' => $role->administrators()->find(Auth::id()) != null,
        ]);
    }

    public function update(StoreUpdateRole $request, Role $role): JsonResponse
    {
        $role->fill($request->validated());
        $role->save();

        $role->users()->sync($request->users);
        $role->administrators()->sync($request->administrators);

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

        return UserResource::collection($role->users()
            ->orderBy('name', 'asc')
            ->get());
    }

    public function administrators(Role $role): JsonResource
    {
        $this->authorize('view', $role);
        $this->authorize('viewAny', User::class);

        return UserResource::collection($role->administrators()
            ->orderBy('name', 'asc')
            ->get());
    }


    public function permissions(): JsonResponse
    {
        $this->authorize('viewAny', Role::class);

        return response()->json(getCategorizedPermissions());
    }
}
