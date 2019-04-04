<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class NavigationServiceProvider extends ServiceProvider
{
    use RegistersNavigationItems, RegisterContextMenus, RegisterContextButtons;

    protected $navigationItems = [
        \App\Navigation\Drawer\HomeNavigationItem::class => 0,
        \App\Navigation\Drawer\PeopleNavigationItem::class => 1,
        \App\Navigation\Drawer\BankNavigationItem::class => 2,
        \App\Navigation\Drawer\ReportingNavigationItem::class => 14,
        \App\Navigation\Drawer\UsersNavigationItem::class => 15,
    ];

    protected $contextMenus = [
        'people.index' => \App\Navigation\ContextMenu\PeopleContextMenu::class,
        'bank.withdrawal' => \App\Navigation\ContextMenu\BankWithdrawalContextMenu::class,
        'bank.withdrawalSearch' => \App\Navigation\ContextMenu\BankWithdrawalContextMenu::class,
    ];

    protected $contextButtons = [
        'changelog' => \App\Navigation\ContextButtons\ChangelogContextButtons::class,

        'userprofile.view2FA' => \App\Navigation\ContextButtons\UserProfile2FAContextButtons::class,

        'users.index' => \App\Navigation\ContextButtons\UserIndexContextButtons::class,
        'users.create' => \App\Navigation\ContextButtons\UserCreateContextButtons::class,
        'users.show' => \App\Navigation\ContextButtons\UserShowContextButtons::class,
        'users.edit' => \App\Navigation\ContextButtons\UserEditContextButtons::class,
        'users.permissions' => \App\Navigation\ContextButtons\UserPermissionsContextButtons::class,

        'roles.index' => \App\Navigation\ContextButtons\RoleIndexContextButtons::class,
        'roles.create' => \App\Navigation\ContextButtons\RoleCreateContextButtons::class,
        'roles.show' => \App\Navigation\ContextButtons\RoleShowContextButtons::class,
        'roles.edit' => \App\Navigation\ContextButtons\RoleEditContextButtons::class,
        'roles.permissions' => \App\Navigation\ContextButtons\RolePermissionsContextButtons::class,

        'people.index' => \App\Navigation\ContextButtons\PeopleIndexContextButtons::class,
        'people.create' => \App\Navigation\ContextButtons\PeopleCreateContextButtons::class,
        'people.show' => \App\Navigation\ContextButtons\PeopleShowContextButtons::class,
        'people.relations' => \App\Navigation\ContextButtons\PeopleRelationsContextButtons::class,
        'people.edit' => \App\Navigation\ContextButtons\PeopleEditContextButtons::class,
        'people.duplicates' => \App\Navigation\ContextButtons\PeopleDuplicatesContextButtons::class,
        'people.import' => \App\Navigation\ContextButtons\PeopleImportContextButtons::class,
        
        'bank.withdrawal' => \App\Navigation\ContextButtons\BankIndexContextButtons::class,
        'bank.withdrawalSearch' => \App\Navigation\ContextButtons\BankIndexContextButtons::class,
        'bank.showCard' => \App\Navigation\ContextButtons\BankIndexContextButtons::class,
        'bank.deposit' => \App\Navigation\ContextButtons\BankDepositContextButtons::class,
        'bank.prepareCodeCard' => \App\Navigation\ContextButtons\BankCodeCardContextButtons::class,
        'bank.settings.edit' => \App\Navigation\ContextButtons\BankSettingsContextButtons::class,
        'bank.withdrawalTransactions' => \App\Navigation\ContextButtons\BankWithdrawalTransactionsContextButtons::class,
        'bank.depositTransactions' => \App\Navigation\ContextButtons\BankDepositTransactionsContextButtons::class,
        'bank.maintenance' => \App\Navigation\ContextButtons\BankMaintenanceContextButtons::class,
        'bank.export' => \App\Navigation\ContextButtons\BankExportContextButtons::class,

        'coupons.index' => \App\Navigation\ContextButtons\CouponIndexContextButtons::class,
        'coupons.create' => \App\Navigation\ContextButtons\CouponCreateContextButtons::class,
        'coupons.show' => \App\Navigation\ContextButtons\CouponShowContextButtons::class,
        'coupons.edit' => \App\Navigation\ContextButtons\CouponEditContextButtons::class,
        
        'reporting.monthly-summary' => \App\Navigation\ContextButtons\ReportingReturnToIndexContextButtons::class,
        'reporting.people' => \App\Navigation\ContextButtons\ReportingReturnToIndexContextButtons::class,
        'reporting.bank.withdrawals' => \App\Navigation\ContextButtons\ReportingReturnToIndexContextButtons::class,
        'reporting.bank.deposits' => \App\Navigation\ContextButtons\ReportingReturnToIndexContextButtons::class,
        'reporting.privacy' => \App\Navigation\ContextButtons\ReportingReturnToIndexContextButtons::class,
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
