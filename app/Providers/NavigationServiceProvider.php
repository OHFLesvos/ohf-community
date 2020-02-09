<?php

namespace App\Providers;

use App\Providers\Traits\RegistersNavigationItems;
use App\Providers\Traits\RegisterContextMenus;
use App\Providers\Traits\RegisterContextButtons;

use Illuminate\Support\ServiceProvider;

class NavigationServiceProvider extends ServiceProvider
{
    use RegistersNavigationItems, RegisterContextMenus, RegisterContextButtons;

    protected $navigationItems = [
        \App\Navigation\Drawer\HomeNavigationItem::class                    => 0,
        \App\Navigation\Drawer\ReportingNavigationItem::class               => 14,
        \App\Navigation\Drawer\UserManagement\UsersNavigationItem::class    => 15,
        \App\Navigation\Drawer\Logviewer\LogViewerNavigationItem::class     => 16,
        \App\Navigation\Drawer\Badges\BadgesNavigationItem::class           => 13,
        \App\Navigation\Drawer\Fundraising\FundraisingNavigationItem::class => 5,
        \App\Navigation\Drawer\Accounting\AccountingNavigationItem::class   => 7,
        \App\Navigation\Drawer\Collaboration\KBItem::class                  => 6,
        \App\Navigation\Drawer\Collaboration\CalendarNavigationItem::class  => 11,
        \App\Navigation\Drawer\Collaboration\TasksNavigationItem::class     => 12,
    ];

    protected $contextMenus = [
    ];

    protected $contextButtons = [
        'userprofile.view2FA' => \App\Navigation\ContextButtons\UserManagement\UserProfile2FAContextButtons::class,

        'users.index'       => \App\Navigation\ContextButtons\UserManagement\UserIndexContextButtons::class,
        'users.create'      => \App\Navigation\ContextButtons\UserManagement\UserCreateContextButtons::class,
        'users.show'        => \App\Navigation\ContextButtons\UserManagement\UserShowContextButtons::class,
        'users.edit'        => \App\Navigation\ContextButtons\UserManagement\UserEditContextButtons::class,
        'users.permissions' => \App\Navigation\ContextButtons\UserManagement\UserPermissionsContextButtons::class,

        'roles.index'       => \App\Navigation\ContextButtons\UserManagement\RoleIndexContextButtons::class,
        'roles.create'      => \App\Navigation\ContextButtons\UserManagement\RoleCreateContextButtons::class,
        'roles.show'        => \App\Navigation\ContextButtons\UserManagement\RoleShowContextButtons::class,
        'roles.edit'        => \App\Navigation\ContextButtons\UserManagement\RoleEditContextButtons::class,
        'roles.permissions' => \App\Navigation\ContextButtons\UserManagement\RolePermissionsContextButtons::class,

        'changelog'         => \App\Navigation\ContextButtons\Changelog\ChangelogContextButtons::class,

        'badges.selection'  => \App\Navigation\ContextButtons\Badges\BadgeSelectionContextButtons::class,

        'fundraising.donors.index'        => \App\Navigation\ContextButtons\Fundraising\DonorIndexContextButtons::class,
        'fundraising.donors.create'       => \App\Navigation\ContextButtons\Fundraising\DonorCreateContextButtons::class,
        'fundraising.donors.show'         => \App\Navigation\ContextButtons\Fundraising\DonorShowContextButtons::class,
        'fundraising.donors.edit'         => \App\Navigation\ContextButtons\Fundraising\DonorEditContextButtons::class,
        'fundraising.donations.index'     => \App\Navigation\ContextButtons\Fundraising\DonationIndexContextButtons::class,
        'fundraising.donations.import'    => \App\Navigation\ContextButtons\Fundraising\DonationImportContextButtons::class,
        'fundraising.donations.create'    => \App\Navigation\ContextButtons\Fundraising\DonationCreateContextButtons::class,
        'fundraising.donations.edit'      => \App\Navigation\ContextButtons\Fundraising\DonationEditContextButtons::class,

        'accounting.transactions.index'   => \App\Navigation\ContextButtons\Accounting\TransactionIndexContextButtons::class,
        'accounting.transactions.summary' => \App\Navigation\ContextButtons\Accounting\TransactionSummaryContextButtons::class,
        'accounting.transactions.create'  => \App\Navigation\ContextButtons\Accounting\TransactionReturnToIndexContextButtons::class,
        'accounting.transactions.export'  => \App\Navigation\ContextButtons\Accounting\TransactionReturnToIndexContextButtons::class,
        'accounting.transactions.show'    => \App\Navigation\ContextButtons\Accounting\TransactionShowContextButtons::class,
        'accounting.transactions.edit'    => \App\Navigation\ContextButtons\Accounting\TransactionEditContextButtons::class,
        'accounting.webling.index'        => \App\Navigation\ContextButtons\Accounting\WeblingIndexContextButtons::class,
        'accounting.webling.prepare'      => \App\Navigation\ContextButtons\Accounting\WeblingPrepareContextButtons::class,
        'accounting.settings.edit'        => \App\Navigation\ContextButtons\Accounting\SettingsContextButtons::class,

        'kb.index'                        => \App\Navigation\ContextButtons\Collaboration\IndexContextButtons::class,
        'kb.latestChanges'                => \App\Navigation\ContextButtons\Collaboration\LatestChangesContextButtons::class,
        'kb.tags'                         => \App\Navigation\ContextButtons\Collaboration\TagsContextButtons::class,
        'kb.tag'                          => \App\Navigation\ContextButtons\Collaboration\TagContextButtons::class,
        'kb.articles.index'               => \App\Navigation\ContextButtons\Collaboration\ArticleIndexContextButtons::class,
        'kb.articles.create'              => \App\Navigation\ContextButtons\Collaboration\ArticleCreateContextButtons::class,
        'kb.articles.show'                => \App\Navigation\ContextButtons\Collaboration\ArticleShowContextButtons::class,
        'kb.articles.edit'                => \App\Navigation\ContextButtons\Collaboration\ArticleEditContextButtons::class,
    ];

    /**
     * Boot the application events.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerNavigationItems();
        $this->registerContextMenus();
        $this->registerContextButtons();
    }
}
