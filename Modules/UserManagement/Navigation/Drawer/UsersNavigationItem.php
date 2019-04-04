<?php

namespace Modules\UserManagement\Navigation\Drawer;

use App\User;
use App\Role;
use App\Navigation\Drawer\BaseNavigationItem;

use Illuminate\Support\Facades\Auth;

class UsersNavigationItem extends BaseNavigationItem {

    protected $route = 'users.index';

    protected $caption = 'app.users_and_roles';

    protected $icon = 'users';

    protected $active = ['admin/users*', 'admin/roles*'];

    public function isAuthorized(): bool
    {
        return Auth::user()->can('list', User::class) || Auth::user()->can('list', Role::class);
    }

}
