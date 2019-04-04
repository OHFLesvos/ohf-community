<?php

namespace Modules\UserManagement\Providers;

use App\Providers\RegistersNavigationItems;
use App\Providers\RegisterContextButtons;

use Illuminate\Support\ServiceProvider;

class NavigationServiceProvider extends ServiceProvider
{
    use RegistersNavigationItems, RegisterContextButtons;

    /**
     * Navigation items
     */
    protected $navigationItems = [
        \Modules\UserManagement\Navigation\Drawer\UsersNavigationItem::class => 15,
    ];

    protected $contextButtons = [
        'userprofile.view2FA' => \Modules\UserManagement\Navigation\ContextButtons\UserProfile2FAContextButtons::class,

        'users.index' => \Modules\UserManagement\Navigation\ContextButtons\UserIndexContextButtons::class,
        'users.create' => \Modules\UserManagement\Navigation\ContextButtons\UserCreateContextButtons::class,
        'users.show' => \Modules\UserManagement\Navigation\ContextButtons\UserShowContextButtons::class,
        'users.edit' => \Modules\UserManagement\Navigation\ContextButtons\UserEditContextButtons::class,
        'users.permissions' => \Modules\UserManagement\Navigation\ContextButtons\UserPermissionsContextButtons::class,

        'roles.index' => \Modules\UserManagement\Navigation\ContextButtons\RoleIndexContextButtons::class,
        'roles.create' => \Modules\UserManagement\Navigation\ContextButtons\RoleCreateContextButtons::class,
        'roles.show' => \Modules\UserManagement\Navigation\ContextButtons\RoleShowContextButtons::class,
        'roles.edit' => \Modules\UserManagement\Navigation\ContextButtons\RoleEditContextButtons::class,
        'roles.permissions' => \Modules\UserManagement\Navigation\ContextButtons\RolePermissionsContextButtons::class,
    ];

    /**
     * Boot the application events.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerNavigationItems();
        $this->registerContextButtons();
    }

}
