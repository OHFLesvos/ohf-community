<?php

namespace Modules\UserManagement\Http\Controllers;

use App\User;
use App\Role;
use App\Http\Controllers\Controller;
use App\Support\Facades\PermissionRegistry;

use Modules\UserManagement\Http\Requests\StoreUser;
use Modules\UserManagement\Http\Requests\UpdateUser;

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

        return view('usermanagement::users.index', [
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
        return view('usermanagement::users.create', [
            'roles' => Role::orderBy('name')->get()
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Modules\UserManagement\Http\Requests\StoreUser  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreUser $request)
    {
        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);
        $user->is_super_admin = !empty($request->is_super_admin);
        $user->save();
        $user->roles()->sync($request->roles);
        return redirect()->route('users.index')
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
        $current_permissions = $user->permissions()->pluck('key');
        $permissions = [];
        foreach (PermissionRegistry::getCategorizedPermissions() as $title => $elements) {
            foreach($elements as $key => $label) {
                if ($current_permissions->contains($key)) {
                    $permissions[$title][] = $label;
                }
            }
        }

        return view('usermanagement::users.show', [
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
        return view('usermanagement::users.edit', [
            'user' => $user,
            'roles' => Role::orderBy('name')->get()
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Modules\UserManagement\Http\Requests\UpdateUser  $request
     * @param  User $user
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateUser $request, User $user)
    {
        $user->name = $request->name;
        if (empty($user->provider_name)) {
            $user->email = $request->email;
        }
        if (!empty($request->password)) {
            $user->password = Hash::make($request->password);
        }
        $user->is_super_admin = !empty($request->is_super_admin) || User::count() == 1;
        $user->roles()->sync($request->roles);
        if ($user->isDirty()) {
            $user->save();
            return redirect()->route('users.show', $user)
                ->with('success', __('app.user_updated'));
        }
        return redirect()->route('users.show', $user)
            ->with('info', __('app.no_changes_made'));
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
        return redirect()->route('users.index')
            ->with('success', __('app.user_deleted'));
    }

    /**
     * Lists all permissions
     */
    public function permissions()
    {
        return view('usermanagement::users.permission_report', [
            'permissions' => PermissionRegistry::getCategorizedPermissions(),
        ]);
    }

    /**
     * Lists all permissions
     */
    public function sensitiveDataReport()
    {
        return view('usermanagement::users.privacy_report', [
            'permissions' => PermissionRegistry::collection(),
            'sensitivePermissions' => PermissionRegistry::collection(true),
            'users' => User::orderBy('name')
                ->get()
                ->filter(function($u) {
                    return $u->isSuperAdmin() || $u->permissions()->contains(function($p) {
                        return PermissionRegistry::hasKey($p->key, true);
                    });
                })
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
        return redirect()->route('users.show', $user)
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
        $password = str_random(8);
        $user->password = Hash::make($password);
        $user->save();
        return redirect()->route('users.show', $user)
            ->with('success', __('userprofile.oauth_disabled_new_password_has_been_set'));
    }
    
}
