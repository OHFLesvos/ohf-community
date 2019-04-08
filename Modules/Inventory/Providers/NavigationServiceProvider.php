<?php

namespace Modules\Inventory\Providers;

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
        \Modules\Inventory\Navigation\Drawer\InventoryStorageNavigationItem::class => 7,
    ];

    protected $contextButtons = [
        'inventory.storages.index'       => \Modules\Inventory\Navigation\ContextButtons\InventoryStorageIndexContextButtons::class,
        'inventory.storages.create'      => \Modules\Inventory\Navigation\ContextButtons\InventoryStorageCreateContextButtons::class,
        'inventory.storages.show'        => \Modules\Inventory\Navigation\ContextButtons\InventoryStorageShowContextButtons::class,
        'inventory.storages.edit'        => \Modules\Inventory\Navigation\ContextButtons\InventoryStorageEditContextButtons::class,
        'inventory.transactions.changes' => \Modules\Inventory\Navigation\ContextButtons\InventoryTransactionChangesContextButtons::class,
        'inventory.transactions.ingress' => \Modules\Inventory\Navigation\ContextButtons\InventoryReturnToStorageContextButtons::class,
        'inventory.transactions.egress'  => \Modules\Inventory\Navigation\ContextButtons\InventoryReturnToStorageContextButtons::class,
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
