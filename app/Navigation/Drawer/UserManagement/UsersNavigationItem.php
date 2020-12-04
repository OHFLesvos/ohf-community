<?php

namespace App\Navigation\Drawer\UserManagement;

use App\Navigation\Drawer\BaseNavigationItem;
use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class UsersNavigationItem extends BaseNavigationItem
{
    protected $route = 'users.index';

    protected $caption = 'app.users_and_roles';

    protected $icon = 'users';

    protected $active = ['admin/users*', 'admin/roles*'];

    public function isAuthorized(): bool
    {
        return Auth::user()->can('viewAny', User::class) || Auth::user()->can('viewAny', Role::class);
    }
}
