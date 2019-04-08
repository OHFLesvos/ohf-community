<?php

namespace Modules\Inventory\Navigation\ContextButtons;

use App\Navigation\ContextButtons\ContextButtons;

use Modules\Inventory\Entities\InventoryItemTransaction;
use Modules\Inventory\Entities\InventoryStorage;

use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;

class InventoryStorageShowContextButtons implements ContextButtons {

    public function getItems(View $view): array
    {
        $storage = $view->getData()['storage'];
        return [
            'action' => [
                'url' => route('inventory.transactions.ingress', $storage),
                'caption' => __('inventory::inventory.store_items'),
                'icon' => 'plus-circle',
                'icon_floating' => 'plus',
                'authorized' => Auth::user()->can('create', InventoryItemTransaction::class),
            ],
            'edit' => [
                'url' => route('inventory.storages.edit', $storage),
                'caption' => __('inventory::inventory.edit_storage'),
                'icon' => 'edit',
                'authorized' => Auth::user()->can('edit', $storage),
            ],
            'delete' => [
                'url' => route('inventory.storages.destroy', $storage),
                'caption' => __('inventory::inventory.delete_storage'),
                'icon' => 'trash',
                'authorized' => Auth::user()->can('delete', $storage),
                'confirmation' => __('inventory::inventory.confirm_delete_storage')
            ],
            'back' => [
                'url' => route('inventory.storages.index'),
                'caption' => __('app.overview'),
                'icon' => 'list',
                'authorized' => Auth::user()->can('list', InventoryStorage::class),
            ]
        ];
    }

}
