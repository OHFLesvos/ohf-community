<?php

namespace App\Http\Controllers\UserManagement;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserManagement\StoreUser;
use App\Http\Requests\UserManagement\UpdateUser;
use App\Models\Role;
use App\Models\User;
use App\View\Components\UserAvatar;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use LasseRafn\InitialAvatarGenerator\InitialAvatar;

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
        $request->validate([
            'sort' => [
                'in:name,created_at',
            ],
            'order' => [
                'in:asc,desc',
            ],
        ]);

        $sort = $request->input('sort', session('usermanagement.users.sort', 'name'));
        $order = $request->input('order', session('usermanagement.users.order', 'asc'));

        session(['usermanagement.users.sort' => $sort]);
        session(['usermanagement.users.order' => $order]);

        return view('user_management.users.index', [
            'users' => User::with(['roles'])
                ->orderBy($sort, $order)
                ->paginate(100),
            'sort' => $sort,
            'order' => $order,
        ]);
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
            ->with('success', __('app.user_added'));
    }

    /**
     * Display the specified resource.
     *
     * @param  User $user
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        $current_permissions = $user->permissions();
        $permissions = [];
        foreach (getCategorizedPermissions() as $title => $elements) {
            foreach ($elements as $key => $label) {
                if ($current_permissions->contains($key)) {
                    $permissions[$title][] = $label;
                }
            }
        }

        return view('user_management.users.show', [
            'user' => $user,
            'permissions' => $permissions,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  User $user
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
     * @param  User $user
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
            ->with('success', __('app.user_updated'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  User $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        $user->delete();

        return redirect()
            ->route('users.index')
            ->with('success', __('app.user_deleted'));
    }

    /**
     * Lists all permissions
     */
    public function permissions()
    {
        return view('user_management.users.permission_report', [
            'permissions' => getCategorizedPermissions(),
        ]);
    }

    /**
     * Lists all permissions
     */
    public function sensitiveDataReport()
    {
        $available_permissions = collect(config('permissions.keys'));
        $sensitive_permissions = $available_permissions->where('sensitive', true);

        return view('user_management.users.privacy_report', [
            'permissions' => $available_permissions->map(fn ($p) => __($p['label'])),
            'sensitivePermissions' => $sensitive_permissions->map(fn ($p) => __($p['label'])),
            'users' => User::orderBy('name')
                ->get()
                ->filter(fn (User $user) => $user->isSuperAdmin() || $user->permissions()->contains(fn ($permission) => $sensitive_permissions->keys()->contains($permission))),
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  User $user
     * @return \Illuminate\Http\Response
     */
    public function disable2FA(User $user)
    {
        $user->tfa_secret = null;
        $user->save();

        return redirect()
            ->route('users.show', $user)
            ->with('success', __('userprofile.tfa_disabled'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  User $user
     * @return \Illuminate\Http\Response
     */
    public function disableOAuth(User $user)
    {
        $user->provider_name = null;
        $user->provider_id = null;
        $user->avatar = null;
        $password = Str::random(8);
        $user->password = Hash::make($password);
        $user->save();

        return redirect()
            ->route('users.show', $user)
            ->with('success', __('userprofile.oauth_disabled_new_password_has_been_set'));
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
