<?php

namespace Modules\Bank\Providers;

use App\Providers\Traits\RegistersNavigationItems;
use App\Providers\Traits\RegisterContextMenus;
use App\Providers\Traits\RegisterContextButtons;

use Illuminate\Support\ServiceProvider;

class NavigationServiceProvider extends ServiceProvider
{
    use RegistersNavigationItems, RegisterContextMenus, RegisterContextButtons;

    /**
     * Navigation items
     */
    protected $navigationItems = [
        \Modules\Bank\Navigation\Drawer\BankNavigationItem::class => 2,
    ];

    protected $contextMenus = [
        'bank.withdrawal' => \Modules\Bank\Navigation\ContextMenu\BankWithdrawalContextMenu::class,
    ];

    protected $contextButtons = [
        'bank.withdrawal' => \Modules\Bank\Navigation\ContextButtons\BankIndexContextButtons::class,
        'bank.deposit' => \Modules\Bank\Navigation\ContextButtons\BankDepositContextButtons::class,
        'bank.prepareCodeCard' => \Modules\Bank\Navigation\ContextButtons\BankCodeCardContextButtons::class,
        'bank.settings.edit' => \Modules\Bank\Navigation\ContextButtons\BankSettingsContextButtons::class,
        'bank.withdrawal.transactions' => \Modules\Bank\Navigation\ContextButtons\BankWithdrawalTransactionsContextButtons::class,
        'bank.depositTransactions' => \Modules\Bank\Navigation\ContextButtons\BankDepositTransactionsContextButtons::class,
        'bank.maintenance' => \Modules\Bank\Navigation\ContextButtons\BankMaintenanceContextButtons::class,
        'bank.export' => \Modules\Bank\Navigation\ContextButtons\BankExportContextButtons::class,

        'coupons.index' => \Modules\Bank\Navigation\ContextButtons\CouponIndexContextButtons::class,
        'coupons.create' => \Modules\Bank\Navigation\ContextButtons\CouponCreateContextButtons::class,
        'coupons.show' => \Modules\Bank\Navigation\ContextButtons\CouponShowContextButtons::class,
        'coupons.edit' => \Modules\Bank\Navigation\ContextButtons\CouponEditContextButtons::class,

        'bank.people.create' => \Modules\Bank\Navigation\ContextButtons\PeopleCreateContextButtons::class,
        'bank.people.show' => \Modules\Bank\Navigation\ContextButtons\PeopleShowContextButtons::class,
        'bank.people.edit' => \Modules\Bank\Navigation\ContextButtons\PeopleEditContextButtons::class,
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
