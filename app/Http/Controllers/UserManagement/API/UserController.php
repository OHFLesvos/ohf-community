<?php

namespace App\Http\Controllers\UserManagement\API;

use App\Http\Controllers\Controller;
use App\Http\Controllers\ValidatesResourceIndex;
use App\Http\Requests\UserManagement\StoreUpdateUser;
use App\Http\Resources\Role as RoleResource;
use App\Http\Resources\User as UserResource;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Log;
use Storage;

class UserController extends Controller
{
    use ValidatesResourceIndex;

    public function __construct()
    {
        $this->authorizeResource(User::class);
    }

    public function index(): JsonResource
    {
        $this->validateFilter();
        $this->validateSorting([
            'name',
            'email',
            'locale',
            'is_super_admin',
            'is_2fa_enabled',
            'provider_name',
            'created_at',
            'updated_at',
        ]);
        $this->validatePagination();

        return UserResource::collection(User::query()
            ->when($this->getFilter() !== null,
                fn (Builder $qry) => $this->filterQuery($qry, $this->getFilter()))
            ->when(in_array('roles', $this->getIncludes()),
                fn (Builder $qry) => $qry->with([
                    'roles' => fn (BelongsToMany $innerQuery) => $innerQuery->orderBy('name'),
                ])
            )
            ->when(in_array('administeredRoles', $this->getIncludes()),
                fn (Builder $qry) => $qry->with([
                    'administeredRoles' => fn (BelongsToMany $innerQuery) => $innerQuery->orderBy('name'),
                ])
            )
            ->orderBy($this->getSortBy('name'), $this->getSortDirection())
            ->paginate($this->getPageSize()));
    }

    private function filterQuery(Builder $query, string $filter): Builder
    {
        return $query
            ->where('name', 'LIKE', '%'.$filter.'%')
            ->orWhere('email', 'LIKE', '%'.$filter.'%');
    }

    public function store(StoreUpdateUser $request): JsonResponse
    {
        $user = new User();
        $user->fill($request->validated());
        $user->password = Hash::make($request->password);
        $user->is_super_admin = ! empty($request->is_super_admin) || User::where('is_super_admin', true)->count() == 1;
        $user->save();
        $user->roles()->sync($request->roles);

        Log::info('User account has been created.', [
            'user_id' => $user->id,
            'user_name' => $user->name,
            'email' => $user->email,
            'client_ip' => $request->ip(),
        ]);

        return response()
            ->json([
                'message' => __('User has been added.'),
                'id' => $user->id,
            ], Response::HTTP_CREATED)
            ->header('Location', route('api.users.show', $user));
    }

    public function show(User $user): JsonResource
    {
        if (in_array('roles', $this->getIncludes())) {
            $user->load(['roles' => fn ($q) => $q->orderBy('name')]);
        }
        if (in_array('administeredRoles', $this->getIncludes())) {
            $user->load(['administeredRoles' => fn ($q) => $q->orderBy('name')]);
        }

        $current_permissions = $user->permissions();
        $permissions = [];
        foreach (getCategorizedPermissions() as $title => $elements) {
            foreach ($elements as $key => $label) {
                if ($current_permissions->contains($key)) {
                    $permissions[$title][] = $label;
                }
            }
        }

        return (new UserResource($user))->additional([
            'permissions' => $permissions,
        ]);
    }

    public function update(StoreUpdateUser $request, User $user): JsonResponse
    {
        $user->fill($request->validated());
        $passwordMessage = '';
        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
            $passwordMessage = ' '.__('A new password has been set.');
        }
        $user->is_super_admin = ! empty($request->is_super_admin) || User::where('is_super_admin', true)->count() == 1;

        if ($request->has('roles')) {
            $user->roles()->sync($request->roles);
        }

        $user->save();

        Log::info('User account has been updated.'.$passwordMessage, [
            'user_id' => $user->id,
            'user_name' => $user->name,
            'email' => $user->email,
            'client_ip' => $request->ip(),
        ]);

        return response()
            ->json([
                'message' => __('User has been updated.').$passwordMessage,
            ]);
    }

    public function disable2FA(Request $request, User $user): JsonResponse
    {
        $this->authorize('update', $user);

        $user->tfa_secret = null;
        $user->save();

        Log::info('Disabled 2FA on user account.', [
            'user_id' => $user->id,
            'user_name' => $user->name,
            'email' => $user->email,
            'client_ip' => $request->ip(),
        ]);

        return response()
            ->json([
                'message' => __('Two-Factor Authentication disabled.'),
            ]);
    }

    public function disableOAuth(Request $request, User $user): JsonResponse
    {
        $this->authorize('update', $user);

        $user->provider_name = null;
        $user->provider_id = null;
        $user->avatar = null;
        $password = Str::random(8);
        $user->password = Hash::make($password);

        $user->save();

        Log::info('Disabled OAuth on user account.', [
            'user_id' => $user->id,
            'user_name' => $user->name,
            'email' => $user->email,
            'client_ip' => $request->ip(),
        ]);

        return response()
            ->json([
                'message' => __('OAuth-Login disabled. A new random password has been set.'),
            ]);
    }

    public function destroy(Request $request, User $user): JsonResponse
    {
        if ($user->avatar !== null && Storage::exists($user->avatar)) {
            Storage::delete($user->avatar);
        }

        $user->delete();

        Log::info('User account has been deleted.', [
            'user_id' => $user->id,
            'user_name' => $user->name,
            'email' => $user->email,
            'client_ip' => $request->ip(),
        ]);

        return response()
            ->json([
                'message' => __('User has been deleted.'),
            ]);
    }

    public function roles(User $user): JsonResource
    {
        $this->authorize('view', $user);
        $this->authorize('viewAny', Role::class);

        return RoleResource::collection($user->roles()
            ->orderBy('name', 'asc')
            ->get());
    }
}
