<?php

namespace App\Navigation\Drawer\UserManagement;

use App\Models\Role;
use App\Models\User;
use App\Navigation\Drawer\BaseNavigationItem;
use Illuminate\Support\Facades\Auth;

class UsersNavigationItem extends BaseNavigationItem
{
    protected string $route = 'users.index';

    public function getCaption(): string
    {
        return __('Users & Roles');
    }

    protected string $icon = 'user-friends';

    protected string|array $active = ['admin/users*', 'admin/roles*'];

    public function isAuthorized(): bool
    {
        return Auth::user()->can('viewAny', User::class) || Auth::user()->can('viewAny', Role::class);
    }
}
