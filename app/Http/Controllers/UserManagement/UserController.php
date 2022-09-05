<?php

namespace App\Http\Controllers\UserManagement;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserManagement\StoreUser;
use App\Http\Requests\UserManagement\UpdateUser;
use App\Models\Role;
use App\Models\User;
use App\Util\AutoColorInitialAvatar;
use App\View\Components\UserAvatar;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(User::class);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        return view('vue-app');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('user_management.users.create', [
            'roles' => Role::orderBy('name')->get(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\UserManagement\StoreUser  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreUser $request)
    {
        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);
        $user->is_super_admin = ! empty($request->is_super_admin);
        $user->save();
        $user->roles()->sync($request->roles);

        return redirect()
            ->route('users.index')
            ->with('success', __('User has been added.'));
    }

    /**
     * Display the specified resource.
     *
     * @param  User  $user
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        return view('vue-app');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  User  $user
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
    {
        return view('user_management.users.edit', [
            'user' => $user,
            'roles' => Role::orderBy('name')->get(),
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UserManagement\UpdateUser  $request
     * @param  User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateUser $request, User $user)
    {
        $user->name = $request->name;
        if (empty($user->provider_name)) {
            $user->email = $request->email;
        }
        if ($request->password !== null) {
            $user->password = Hash::make($request->password);
        }
        $user->is_super_admin = ! empty($request->is_super_admin) || User::count() == 1;
        $user->roles()->sync($request->roles);

        $user->save();

        return redirect()
            ->route('users.show', $user)
            ->with('success', __('User has been updated.'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        $user->delete();

        return redirect()
            ->route('users.index')
            ->with('success', __('User has been deleted.'));
    }

    /**
     * Lists all permissions
     */
    public function permissions()
    {
        $this->authorize('viewAny', User::class);

        return view('user_management.users.list-permissions', [
            'permissions' => getCategorizedPermissions(),
        ]);
    }

    /**
     * Display the avatar of the user.
     *
     * @param  \App\Models\User  $user
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

        $avatar = new AutoColorInitialAvatar();

        return $avatar->name($user->name)
            ->size($request->input('size', UserAvatar::DEFAULT_SIZE))
            ->autoColor()
            ->generate()
            ->response('png', 100);
    }
}
