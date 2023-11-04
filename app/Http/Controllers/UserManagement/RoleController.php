<?php

namespace App\Http\Controllers\UserManagement;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserManagement\UpdateMembers;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class RoleController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Role::class);
    }

    public function permissions(): View
    {
        $this->authorize('viewAny', Role::class);

        return view('user_management.roles.list-permissions', [
            'permissions' => getCategorizedPermissions(),
        ]);
    }

    public function manageMembers(Role $role): View
    {
        $this->authorize('manageMembers', $role);

        return view('user_management.roles.manage-members', [
            'role' => $role,
            'users' => User::orderBy('name')
                ->pluck('name', 'id')
                ->toArray(),
        ]);
    }

    public function updateMembers(UpdateMembers $request, Role $role): RedirectResponse
    {
        $this->authorize('manageMembers', $role);

        $role->users()->sync($request->users);

        return redirect()
            ->route('roles.show', $role)
            ->with('success', __('Role has been updated.'));
    }
}
