<?php

namespace App\Http\Controllers\UserManagement\API;

use App\Http\Controllers\Controller;
use App\Http\Controllers\ValidatesResourceIndex;
use App\Http\Requests\UserManagement\StoreUser;
use App\Http\Requests\UserManagement\UpdateUser;
use App\Http\Resources\RoleCollection;
use App\Http\Resources\User as UserResource;
use App\Http\Resources\UserCollection;
use App\Models\Role;
use App\Models\User;
use App\View\Components\UserAvatar;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use LasseRafn\InitialAvatarGenerator\InitialAvatar;

class UserController extends Controller
{
    use ValidatesResourceIndex;

    public function __construct()
    {
        $this->authorizeResource(User::class);
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
            ->when(in_array('roles', $this->getIncludes()), fn ($qry) => $qry->with(['roles' => fn ($qry) => $qry->orderBy('name')]))
            ->orderBy($this->getSortBy('name'), $this->getSortDirection())
            ->paginate($this->getPageSize()));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \App\Http\Requests\UserManagement\StoreUser $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreUser $request)
    {
        $user = new User();
        $user->fill($request->all());
        $user->password = Hash::make($request->password);

        $user->save();

        return response()
            ->json([
                'message' => __('app.user_added'),
            ], Response::HTTP_CREATED)
            ->header('Location', route('api.users.show', $user));
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Models\User $user
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        return new UserResource($user);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \App\Http\Requests\UserManagement\UpdateUser $request
     * @param \App\Models\User $user
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateUser $request, User $user)
    {
        $user->fill($request->all());
        if ($request->password !== null) {
            $user->password = Hash::make($request->password);
        }

        $user->save();

        return response()
            ->json([
                'message' => __('app.user_updated'),
            ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\User $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        $user->delete();

        return response()
            ->json([
                'message' => __('app.user_deleted'),
            ]);
    }

    /**
     * Display a listing of the resource.
     *
     * @param \App\Models\User $user
     * @return \Illuminate\Http\Response
     */
    public function roles(User $user)
    {
        $this->authorize('view', $user);
        $this->authorize('viewAny', Role::class);

        return new RoleCollection($user->roles()
            ->orderBy('name', 'asc')
            ->get());
    }

    /**
     * Display the avatar of the user.
     *
     * @param \App\Models\User $user
     * @return \Illuminate\Http\Response
     */
    public function avatar(User $user, Request $request)
    {
        $request->validate([
            'size' => [
                'nullable',
                'integer',
                'min:10',
                'max:250',
            ],
        ]);

        $avatar = new InitialAvatar();
        return $avatar->name($user->name)
            ->size($request->input('size', UserAvatar::DEFAULT_SIZE))
            ->generate()
            ->response('png', 100);
    }
}
