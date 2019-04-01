<?php

namespace Modules\Inventory\Navigation\Drawer;

use App\Navigation\Drawer\BaseNavigationItem;

use Modules\Inventory\Entities\InventoryStorage;

use Illuminate\Support\Facades\Auth;

class InventoryStorageNavigationItem extends BaseNavigationItem {

    protected $route = 'inventory.storages.index';

    protected $caption = 'inventory::inventory.inventory_management';

    protected $icon = 'archive';

    protected $active = 'inventory/*';

    public function isAuthorized(): bool
    {
        return Auth::user()->can('list', InventoryStorage::class);
    }

}
