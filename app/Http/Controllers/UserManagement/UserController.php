<?php

namespace App\Http\Controllers\UserManagement;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserManagement\StoreUpdateUser;
use App\Models\Role;
use App\Models\User;
use App\Util\AutoColorInitialAvatar;
use App\View\Components\UserAvatar;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Storage;

class UserController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(User::class);
    }

    public function index(Request $request): View
    {
        return view('vue-app');
    }

    public function create(): View
    {
        return view('user_management.users.create', [
            'roles' => Role::orderBy('name')->get(),
        ]);
    }

    public function store(StoreUpdateUser $request): RedirectResponse
    {
        $user = new User();
        $user->fill($request->validated());
        $user->password = Hash::make($request->password);
        $user->save();
        $user->roles()->sync($request->roles);

        Log::info('User account has been created.', [
            'user_id' => $user->id,
            'user_name' => $user->name,
            'email' => $user->email,
            'client_ip' => $request->ip(),
        ]);

        return redirect()
            ->route('users.index')
            ->with('success', __('User has been added.'));
    }

    public function show(User $user): View
    {
        return view('vue-app');
    }

    public function edit(User $user): View
    {
        return view('user_management.users.edit', [
            'user' => $user,
            'roles' => Role::orderBy('name')->get(),
        ]);
    }

    public function update(StoreUpdateUser $request, User $user): RedirectResponse
    {
        $user->fill($request->validated());
        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }
        $user->is_super_admin = ! empty($request->is_super_admin) || User::count() == 1;
        $user->roles()->sync($request->roles);

        $user->save();

        Log::info('User account has been updated.', [
            'user_id' => $user->id,
            'user_name' => $user->name,
            'email' => $user->email,
            'client_ip' => $request->ip(),
        ]);

        return redirect()
            ->route('users.show', $user)
            ->with('success', __('User has been updated.'));
    }

    public function destroy(Request $request, User $user): RedirectResponse
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

        return redirect()
            ->route('users.index')
            ->with('success', __('User has been deleted.'));
    }

    public function permissions(): View
    {
        $this->authorize('viewAny', User::class);

        return view('user_management.users.list-permissions', [
            'permissions' => getCategorizedPermissions(),
        ]);
    }

    public function avatar(User $user, Request $request): Response
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
