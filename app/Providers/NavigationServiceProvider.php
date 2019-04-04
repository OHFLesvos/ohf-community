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
    ];

    protected $contextMenus = [
        'people.index' => \App\Navigation\ContextMenu\PeopleContextMenu::class,
        'bank.withdrawal' => \App\Navigation\ContextMenu\BankWithdrawalContextMenu::class,
        'bank.withdrawalSearch' => \App\Navigation\ContextMenu\BankWithdrawalContextMenu::class,
    ];

    protected $contextButtons = [
        'changelog' => \App\Navigation\ContextButtons\ChangelogContextButtons::class,

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
