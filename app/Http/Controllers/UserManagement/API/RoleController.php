<?php

namespace App\Http\Controllers\UserManagement\API;

use App\Http\Controllers\Controller;
use App\Http\Controllers\ValidatesResourceIndex;
use App\Http\Requests\UserManagement\StoreUpdateRole;
use App\Http\Resources\Role as RoleResource;
use App\Http\Resources\User as UserResource;
use App\Models\Role;
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
                    $permissions[$title][] = $label;
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
}
