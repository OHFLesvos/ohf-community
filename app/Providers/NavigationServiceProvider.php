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
        \App\Navigation\Drawer\People\PeopleNavigationItem::class,
        \App\Navigation\Drawer\Bank\BankNavigationItem::class,
        \App\Navigation\Drawer\CommunityVolunteers\CommunityVolunteersNavigationItem::class,
        \App\Navigation\Drawer\Fundraising\FundraisingNavigationItem::class,
        \App\Navigation\Drawer\Collaboration\KBItem::class,
        \App\Navigation\Drawer\Accounting\AccountingNavigationItem::class,
        \App\Navigation\Drawer\Accounting\SuppliersNavigationItem::class,
        \App\Navigation\Drawer\Shop\ShopNavigationItem::class,
        \App\Navigation\Drawer\Visitors\VisitorsNavigationItem::class,
        \App\Navigation\Drawer\Library\LibraryNavigationItem::class,
        \App\Navigation\Drawer\Badges\BadgesNavigationItem::class,
        \App\Navigation\Drawer\ReportsNavigationItem::class,
        \App\Navigation\Drawer\UserManagement\UsersNavigationItem::class,
        \App\Navigation\Drawer\Settings\SettingsNavigationItem::class,
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
        'people.maintenance'              => \App\Navigation\ContextButtons\People\PeopleMaintenanceContextButtons::class,
        'people.import-export'            => \App\Navigation\ContextButtons\People\PeopleImportContextButtons::class,

        'bank.withdrawal.search'          => \App\Navigation\ContextButtons\Bank\BankIndexContextButtons::class,
        'bank.prepareCodeCard'            => \App\Navigation\ContextButtons\Bank\BankCodeCardContextButtons::class,
        'bank.withdrawal.transactions'    => \App\Navigation\ContextButtons\Bank\BankWithdrawalTransactionsContextButtons::class,
        'bank.export'                     => \App\Navigation\ContextButtons\Bank\BankExportContextButtons::class,

        'bank.coupons.index'                   => \App\Navigation\ContextButtons\Bank\CouponIndexContextButtons::class,
        'bank.coupons.create'                  => \App\Navigation\ContextButtons\Bank\CouponCreateContextButtons::class,
        'bank.coupons.edit'                    => \App\Navigation\ContextButtons\Bank\CouponEditContextButtons::class,

        'bank.people.create'              => \App\Navigation\ContextButtons\Bank\PeopleCreateContextButtons::class,
        'bank.people.show'                => \App\Navigation\ContextButtons\Bank\PeopleShowContextButtons::class,
        'bank.people.edit'                => \App\Navigation\ContextButtons\Bank\PeopleEditContextButtons::class,

        'cmtyvol.index'            => \App\Navigation\ContextButtons\CommunityVolunteers\CommunityVolunteersIndexContextButtons::class,
        'cmtyvol.show'             => \App\Navigation\ContextButtons\CommunityVolunteers\CommunityVolunteersShowContextButtons::class,
        'cmtyvol.edit'             => \App\Navigation\ContextButtons\CommunityVolunteers\CommunityVolunteersEditContextButtons::class,
        'cmtyvol.create'           => \App\Navigation\ContextButtons\CommunityVolunteers\CommunityVolunteersReturnToIndexContextButtons::class,
        'cmtyvol.import-export'    => \App\Navigation\ContextButtons\CommunityVolunteers\CommunityVolunteersReturnToIndexContextButtons::class,
        'cmtyvol.responsibilities.index'  => \App\Navigation\ContextButtons\CommunityVolunteers\ResponsibilitiesIndexContextButtons::class,
        'cmtyvol.responsibilities.create' => \App\Navigation\ContextButtons\CommunityVolunteers\ResponsibilitiesCreateContextButtons::class,
        'cmtyvol.responsibilities.edit'   => \App\Navigation\ContextButtons\CommunityVolunteers\ResponsibilitiesEditContextButtons::class,

        'visitors.index'                  => \App\Navigation\ContextButtons\Visitors\VisitorIndexContextButtons::class,
        'visitors.any'                    => \App\Navigation\ContextButtons\Visitors\VisitorIndexContextButtons::class,

        'shop.index'                      => \App\Navigation\ContextButtons\Shop\ShopContextButtons::class,
        'shop.manageCards'                => \App\Navigation\ContextButtons\Shop\ShopManageCardsContextButtons::class,

        'reports.people.monthly-summary'  => \App\Navigation\ContextButtons\ReportingReturnToIndexContextButtons::class,
        'reports.people.people'           => \App\Navigation\ContextButtons\ReportingReturnToIndexContextButtons::class,
        'reports.bank.visitors'           => \App\Navigation\ContextButtons\ReportingReturnToIndexContextButtons::class,
        'reports.bank.withdrawals'        => \App\Navigation\ContextButtons\ReportingReturnToIndexContextButtons::class,
        'reports.cmtyvol.report'          => \App\Navigation\ContextButtons\ReportingReturnToIndexContextButtons::class,
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
