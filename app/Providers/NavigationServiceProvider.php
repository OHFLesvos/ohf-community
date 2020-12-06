<?php

namespace App\Providers;

use App\Providers\Traits\RegisterContextButtons;
use App\Providers\Traits\RegisterContextMenus;
use App\Providers\Traits\RegistersNavigationItems;
use Illuminate\Support\ServiceProvider;

class NavigationServiceProvider extends ServiceProvider
{
    use RegistersNavigationItems, RegisterContextMenus, RegisterContextButtons;

    protected $navigationItems = [
        \App\Navigation\Drawer\HomeNavigationItem::class                    => 0,
        \App\Navigation\Drawer\ReportingNavigationItem::class               => 14,
        \App\Navigation\Drawer\UserManagement\UsersNavigationItem::class    => 15,
        \App\Navigation\Drawer\Settings\SettingsNavigationItem::class       => 16,
        \App\Navigation\Drawer\Badges\BadgesNavigationItem::class           => 13,
        \App\Navigation\Drawer\Fundraising\FundraisingNavigationItem::class => 5,
        \App\Navigation\Drawer\Accounting\AccountingNavigationItem::class   => 7,
        \App\Navigation\Drawer\Accounting\SuppliersNavigationItem::class    => 7,
        \App\Navigation\Drawer\Collaboration\KBItem::class                  => 6,
        \App\Navigation\Drawer\People\PeopleNavigationItem::class           => 1,
        \App\Navigation\Drawer\Bank\BankNavigationItem::class               => 2,
        \App\Navigation\Drawer\CommunityVolunteers\CommunityVolunteersNavigationItem::class         => 3,
        \App\Navigation\Drawer\Library\LibraryNavigationItem::class         => 10,
        \App\Navigation\Drawer\Shop\ShopNavigationItem::class               => 8,
        \App\Navigation\Drawer\Visitors\VisitorsNavigationItem::class       => 9,
    ];

    protected $contextMenus = [
        'people.index'           => \App\Navigation\ContextMenu\People\PeopleContextMenu::class,
        'bank.withdrawal.search' => \App\Navigation\ContextMenu\Bank\BankWithdrawalContextMenu::class,
        'cmtyvol.index'   => \App\Navigation\ContextMenu\CommunityVolunteers\CommunityVolunteersContextMenu::class,
        'accounting.transactions.index'       => \App\Navigation\ContextMenu\Accounting\AccountingContextMenu::class,
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

        'accounting.transactions.index'         => \App\Navigation\ContextButtons\Accounting\TransactionIndexContextButtons::class,
        'accounting.transactions.summary'       => \App\Navigation\ContextButtons\Accounting\TransactionSummaryContextButtons::class,
        'accounting.transactions.globalSummary' => \App\Navigation\ContextButtons\Accounting\TransactionGlobalSummaryContextButtons::class,
        'accounting.transactions.create'        => \App\Navigation\ContextButtons\Accounting\TransactionReturnToIndexContextButtons::class,
        'accounting.transactions.export'        => \App\Navigation\ContextButtons\Accounting\TransactionReturnToIndexContextButtons::class,
        'accounting.transactions.show'          => \App\Navigation\ContextButtons\Accounting\TransactionShowContextButtons::class,
        'accounting.transactions.edit'          => \App\Navigation\ContextButtons\Accounting\TransactionEditContextButtons::class,
        'accounting.webling.index'              => \App\Navigation\ContextButtons\Accounting\WeblingIndexContextButtons::class,
        'accounting.webling.prepare'            => \App\Navigation\ContextButtons\Accounting\WeblingPrepareContextButtons::class,
        'accounting.index'                      => \App\Navigation\ContextButtons\Accounting\IndexContextButtons::class,
        'accounting.wallets'                    => \App\Navigation\ContextButtons\Accounting\WalletsContextButtons::class,
        'accounting.wallets.any'                => \App\Navigation\ContextButtons\Accounting\WalletsContextButtons::class,
        'accounting.suppliers'                  => \App\Navigation\ContextButtons\Accounting\SuppliersContextButtons::class,
        'accounting.suppliers.show'             => \App\Navigation\ContextButtons\Accounting\SuppliersContextButtons::class,
        'accounting.suppliers.any'              => \App\Navigation\ContextButtons\Accounting\SuppliersContextButtons::class,

        'kb.index'                        => \App\Navigation\ContextButtons\Collaboration\IndexContextButtons::class,
        'kb.latestChanges'                => \App\Navigation\ContextButtons\Collaboration\LatestChangesContextButtons::class,
        'kb.tags'                         => \App\Navigation\ContextButtons\Collaboration\TagsContextButtons::class,
        'kb.tag'                          => \App\Navigation\ContextButtons\Collaboration\TagContextButtons::class,
        'kb.articles.index'               => \App\Navigation\ContextButtons\Collaboration\ArticleIndexContextButtons::class,
        'kb.articles.create'              => \App\Navigation\ContextButtons\Collaboration\ArticleCreateContextButtons::class,
        'kb.articles.show'                => \App\Navigation\ContextButtons\Collaboration\ArticleShowContextButtons::class,
        'kb.articles.edit'                => \App\Navigation\ContextButtons\Collaboration\ArticleEditContextButtons::class,

        'people.index'                    => \App\Navigation\ContextButtons\People\PeopleIndexContextButtons::class,
        'people.create'                   => \App\Navigation\ContextButtons\People\PeopleCreateContextButtons::class,
        'people.show'                     => \App\Navigation\ContextButtons\People\PeopleShowContextButtons::class,
        'people.edit'                     => \App\Navigation\ContextButtons\People\PeopleEditContextButtons::class,
        'people.duplicates'               => \App\Navigation\ContextButtons\People\PeopleDuplicatesContextButtons::class,
        'people.import'                   => \App\Navigation\ContextButtons\People\PeopleImportContextButtons::class,
        'people.bulkSearch'               => \App\Navigation\ContextButtons\People\PeopleCreateContextButtons::class,
        'people.doBulkSearch'             => \App\Navigation\ContextButtons\People\PeopleCreateContextButtons::class,

        'reporting.monthly-summary'       => \App\Navigation\ContextButtons\ReportingReturnToIndexContextButtons::class,
        'reporting.people'                => \App\Navigation\ContextButtons\ReportingReturnToIndexContextButtons::class,
        'reporting.bank.visitors'         => \App\Navigation\ContextButtons\ReportingReturnToIndexContextButtons::class,
        'reporting.bank.withdrawals'      => \App\Navigation\ContextButtons\ReportingReturnToIndexContextButtons::class,
        'reporting.privacy'               => \App\Navigation\ContextButtons\ReportingReturnToIndexContextButtons::class,

        'bank.withdrawal.search'          => \App\Navigation\ContextButtons\Bank\BankIndexContextButtons::class,
        'bank.prepareCodeCard'            => \App\Navigation\ContextButtons\Bank\BankCodeCardContextButtons::class,
        'bank.withdrawal.transactions'    => \App\Navigation\ContextButtons\Bank\BankWithdrawalTransactionsContextButtons::class,
        'bank.maintenance'                => \App\Navigation\ContextButtons\Bank\BankMaintenanceContextButtons::class,
        'bank.export'                     => \App\Navigation\ContextButtons\Bank\BankExportContextButtons::class,

        'coupons.index'                   => \App\Navigation\ContextButtons\Bank\CouponIndexContextButtons::class,
        'coupons.create'                  => \App\Navigation\ContextButtons\Bank\CouponCreateContextButtons::class,
        'coupons.edit'                    => \App\Navigation\ContextButtons\Bank\CouponEditContextButtons::class,

        'bank.people.create'              => \App\Navigation\ContextButtons\Bank\PeopleCreateContextButtons::class,
        'bank.people.show'                => \App\Navigation\ContextButtons\Bank\PeopleShowContextButtons::class,
        'bank.people.edit'                => \App\Navigation\ContextButtons\Bank\PeopleEditContextButtons::class,

        'cmtyvol.index'            => \App\Navigation\ContextButtons\CommunityVolunteers\CommunityVolunteersIndexContextButtons::class,
        'cmtyvol.show'             => \App\Navigation\ContextButtons\CommunityVolunteers\CommunityVolunteersShowContextButtons::class,
        'cmtyvol.edit'             => \App\Navigation\ContextButtons\CommunityVolunteers\CommunityVolunteersEditContextButtons::class,
        'cmtyvol.create'           => \App\Navigation\ContextButtons\CommunityVolunteers\CommunityVolunteersReturnToIndexContextButtons::class,
        'cmtyvol.import'           => \App\Navigation\ContextButtons\CommunityVolunteers\CommunityVolunteersReturnToIndexContextButtons::class,
        'cmtyvol.export'           => \App\Navigation\ContextButtons\CommunityVolunteers\CommunityVolunteersReturnToIndexContextButtons::class,
        'cmtyvol.report'           => \App\Navigation\ContextButtons\CommunityVolunteers\CommunityVolunteersReturnToIndexContextButtons::class,
        'cmtyvol.responsibilities.index'  => \App\Navigation\ContextButtons\CommunityVolunteers\ResponsibilitiesIndexContextButtons::class,
        'cmtyvol.responsibilities.create' => \App\Navigation\ContextButtons\CommunityVolunteers\ResponsibilitiesCreateContextButtons::class,
        'cmtyvol.responsibilities.edit'   => \App\Navigation\ContextButtons\CommunityVolunteers\ResponsibilitiesEditContextButtons::class,

        'visitors.index'                  => \App\Navigation\ContextButtons\Visitors\VisitorIndexContextButtons::class,
        'visitors.any'                    => \App\Navigation\ContextButtons\Visitors\VisitorIndexContextButtons::class,

        'shop.index'                      => \App\Navigation\ContextButtons\Shop\ShopContextButtons::class,
        'shop.manageCards'                => \App\Navigation\ContextButtons\Shop\ShopManageCardsContextButtons::class,
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
