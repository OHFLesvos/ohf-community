<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\ParentController;
use App\Http\Requests\Admin\StoreUser;
use App\Http\Requests\Admin\UpdateUser;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Config;
use App\User;
use App\Role;

class UserController extends ParentController
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->authorizeResource(User::class);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('users.index', [
            'users' => User::with(['roles'])->orderBy('name')->paginate()
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('users.create', [
            'roles' => Role::orderBy('name')->get()
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\Admin\StoreUser  $request
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
        foreach (RoleController::getCategorizedPermissions() as $title => $elements) {
            foreach($elements as $key => $label) {
                if ($current_permissions->contains($key)) {
                    $permissions[$title][] = $label;
                }
            }
        }

        return view('users.show', [
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
        return view('users.edit', [
            'user' => $user,
            'roles' => Role::orderBy('name')->get()
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\Admin\UpdateUser  $request
     * @param  User $user
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateUser $request, User $user)
    {
        $user->name = $request->name;
        $user->email = $request->email;
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
        return view('users.permissions', [
            'permissions' => RoleController::getCategorizedPermissions(),
        ]);
    }

    /**
     * Lists all permissions
     */
    public function sensitiveDataReport()
    {
        $permissions = Config::get('auth.permissions');
        return view('reporting.privacy', [
            'permissions' => Config::get('auth.permissions'),
            'users' => User::orderBy('name')
                ->get()
                ->filter(function($u) use($permissions) {
                    return $u->isSuperAdmin() || $u->permissions()->contains(function($p) use($permissions) {
                        return isset($permissions[$p->key]) && $permissions[$p->key]['sensitive'];
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
}
