<?php

namespace App\Http\Controllers\UserManagement\API;

use App\Http\Controllers\Controller;
use App\Http\Controllers\ValidatesResourceIndex;
use App\Http\Requests\UserManagement\StoreUpdateUser;
use App\Http\Resources\RoleCollection;
use App\Http\Resources\User as UserResource;
use App\Http\Resources\UserCollection;
use App\Models\Role;
use App\Models\User;
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

        return new UserCollection(User::filtered($this->getFilter())
            ->when(in_array('roles', $this->getIncludes()), fn ($qry) => $qry->with([
                'roles' => fn ($qry) => $qry->orderBy('name'),
            ]))
            ->when(in_array('administeredRoles', $this->getIncludes()), fn ($qry) => $qry->with([
                'administeredRoles' => fn ($qry) => $qry->orderBy('name'),
            ]))
            ->orderBy($this->getSortBy('name'), $this->getSortDirection())
            ->paginate($this->getPageSize()));
    }

    public function store(StoreUpdateUser $request): JsonResponse
    {
        $user = new User();
        $user->fill($request->validated());
        $user->password = Hash::make($request->password);

        $user->save();

        Log::info('User account has been created.', [
            'user_id' => $user->id,
            'user_name' => $user->name,
            'email' => $user->email,
            'client_ip' => $request->ip(),
        ]);

        return response()
            ->json([
                'message' => __('User has been added.'),
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
        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        $user->save();

        Log::info('User account has been updated.', [
            'user_id' => $user->id,
            'user_name' => $user->name,
            'email' => $user->email,
            'client_ip' => $request->ip(),
        ]);

        return response()
            ->json([
                'message' => __('User has been updated.'),
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

        return new RoleCollection($user->roles()
            ->orderBy('name', 'asc')
            ->get());
    }
}
