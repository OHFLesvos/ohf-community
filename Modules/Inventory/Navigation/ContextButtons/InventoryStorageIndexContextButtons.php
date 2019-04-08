<?php

namespace Modules\Inventory\Navigation\ContextButtons;

use App\Navigation\ContextButtons\ContextButtons;

use Modules\Inventory\Entities\InventoryStorage;

use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;

class InventoryStorageIndexContextButtons implements ContextButtons {

    public function getItems(View $view): array
    {
        return [
            'add' => [
                'url' => route('inventory.storages.create'),
                'caption' => __('inventory::inventory.add_storage'),
                'icon' => 'plus-circle',
                'authorized' => Auth::user()->can('create', InventoryStorage::class),
            ],
        ];
    }

}
