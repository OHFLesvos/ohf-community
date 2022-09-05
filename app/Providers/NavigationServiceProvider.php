<?php

namespace App\Providers;

use App\Providers\Traits\RegisterContextButtons;
use App\Providers\Traits\RegistersNavigationItems;
use Illuminate\Support\ServiceProvider;

class NavigationServiceProvider extends ServiceProvider
{
    use RegistersNavigationItems, RegisterContextButtons;

    protected $navigationItems = [
        \App\Navigation\Drawer\HomeNavigationItem::class,
        \App\Navigation\Drawer\Visitors\VisitorsNavigationItem::class,
        \App\Navigation\Drawer\CommunityVolunteers\CommunityVolunteersNavigationItem::class,
        \App\Navigation\Drawer\Accounting\AccountingNavigationItem::class,
        \App\Navigation\Drawer\Fundraising\FundraisingNavigationItem::class,
        \App\Navigation\Drawer\Badges\BadgesNavigationItem::class,
        \App\Navigation\Drawer\ReportsNavigationItem::class,
        \App\Navigation\Drawer\UserManagement\UsersNavigationItem::class,
        \App\Navigation\Drawer\Settings\SettingsNavigationItem::class,
    ];

    protected $contextButtons = [
        'users.index'       => \App\Navigation\ContextButtons\UserManagement\UserIndexContextButtons::class,
        'users.create'      => \App\Navigation\ContextButtons\UserManagement\UserCreateContextButtons::class,
        'users.edit'        => \App\Navigation\ContextButtons\UserManagement\UserEditContextButtons::class,
        'users.permissions' => \App\Navigation\ContextButtons\UserManagement\UserPermissionsContextButtons::class,

        'roles.index'       => \App\Navigation\ContextButtons\UserManagement\RoleIndexContextButtons::class,
        'roles.create'      => \App\Navigation\ContextButtons\UserManagement\RoleCreateContextButtons::class,
        'roles.show'        => \App\Navigation\ContextButtons\UserManagement\RoleShowContextButtons::class,
        'roles.edit'        => \App\Navigation\ContextButtons\UserManagement\RoleEditContextButtons::class,
        'roles.permissions' => \App\Navigation\ContextButtons\UserManagement\RolePermissionsContextButtons::class,

        'badges.selection'  => \App\Navigation\ContextButtons\Badges\BadgeSelectionContextButtons::class,

        'accounting.webling.index'              => \App\Navigation\ContextButtons\Accounting\WeblingIndexContextButtons::class,
        'accounting.webling.prepare'            => \App\Navigation\ContextButtons\Accounting\WeblingPrepareContextButtons::class,
        'accounting.suppliers'                  => \App\Navigation\ContextButtons\Accounting\SuppliersContextButtons::class,
        'accounting.suppliers.show'             => \App\Navigation\ContextButtons\Accounting\SuppliersContextButtons::class,
        'accounting.suppliers.any'              => \App\Navigation\ContextButtons\Accounting\SuppliersContextButtons::class,

        'cmtyvol.index'            => \App\Navigation\ContextButtons\CommunityVolunteers\CommunityVolunteersIndexContextButtons::class,
        'cmtyvol.show'             => \App\Navigation\ContextButtons\CommunityVolunteers\CommunityVolunteersShowContextButtons::class,
        'cmtyvol.edit'             => \App\Navigation\ContextButtons\CommunityVolunteers\CommunityVolunteersEditContextButtons::class,
        'cmtyvol.responsibilities' => \App\Navigation\ContextButtons\CommunityVolunteers\CommunityVolunteersEditContextButtons::class,
        'cmtyvol.create'           => \App\Navigation\ContextButtons\CommunityVolunteers\CommunityVolunteersReturnToIndexContextButtons::class,
        'cmtyvol.import-export'    => \App\Navigation\ContextButtons\CommunityVolunteers\CommunityVolunteersReturnToIndexContextButtons::class,
        'cmtyvol.responsibilities.index'  => \App\Navigation\ContextButtons\CommunityVolunteers\ResponsibilitiesIndexContextButtons::class,
        'cmtyvol.responsibilities.create' => \App\Navigation\ContextButtons\CommunityVolunteers\ResponsibilitiesCreateContextButtons::class,
        'cmtyvol.responsibilities.edit'   => \App\Navigation\ContextButtons\CommunityVolunteers\ResponsibilitiesEditContextButtons::class,

        'visitors.index'                  => \App\Navigation\ContextButtons\Visitors\VisitorIndexContextButtons::class,
        'visitors.any'                    => \App\Navigation\ContextButtons\Visitors\VisitorIndexContextButtons::class,

        'reports.cmtyvol.report'          => \App\Navigation\ContextButtons\ReportingReturnToIndexContextButtons::class,
        'reports.visitors.checkins'       => \App\Navigation\ContextButtons\ReportingReturnToIndexContextButtons::class,
        'reports.fundraising.donations'   => \App\Navigation\ContextButtons\ReportingReturnToIndexContextButtons::class,
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
