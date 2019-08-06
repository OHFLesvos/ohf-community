<?php

namespace Modules\Accounting\Providers;

use App\Providers\Traits\RegistersNavigationItems;
use App\Providers\Traits\RegisterContextButtons;

use Illuminate\Support\ServiceProvider;

class NavigationServiceProvider extends ServiceProvider
{
    use RegistersNavigationItems, RegisterContextButtons;

    /**
     * Navigation items
     */
    protected $navigationItems = [
        \Modules\Accounting\Navigation\Drawer\AccountingNavigationItem::class => 7,
    ];

    protected $contextButtons = [
        'accounting.transactions.index' => \Modules\Accounting\Navigation\ContextButtons\TransactionIndexContextButtons::class,
        'accounting.transactions.summary' => \Modules\Accounting\Navigation\ContextButtons\TransactionSummaryContextButtons::class,
        'accounting.transactions.create' => \Modules\Accounting\Navigation\ContextButtons\TransactionReturnToIndexContextButtons::class,
        'accounting.transactions.export' => \Modules\Accounting\Navigation\ContextButtons\TransactionReturnToIndexContextButtons::class,
        'accounting.transactions.show' => \Modules\Accounting\Navigation\ContextButtons\TransactionShowContextButtons::class,
        'accounting.transactions.edit' => \Modules\Accounting\Navigation\ContextButtons\TransactionEditContextButtons::class,
        'accounting.transactions.editReceipt' => \Modules\Accounting\Navigation\ContextButtons\TransactionEditReceiptContextButtons::class,
        'accounting.webling.index' => \Modules\Accounting\Navigation\ContextButtons\TransactionReturnToIndexContextButtons::class,
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
