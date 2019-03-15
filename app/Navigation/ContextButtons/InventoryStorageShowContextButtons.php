<?php

namespace App\Navigation\ContextButtons;

use App\InventoryStorage;

use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;

class InventoryStorageShowContextButtons implements ContextButtons {

    public function getItems(View $view): array
    {
        $storage = $view->getData()['storage'];
        return [
            'action' => [
                'url' => route('inventory.transactions.ingress', $storage),
                'caption' => __('inventory.store_items'),
                'icon' => 'plus-circle',
                'icon_floating' => 'plus',
                'authorized' => Auth::user()->can('create', InventoryItemTransaction::class),
            ],
            'edit' => [
                'url' => route('inventory.storages.edit', $storage),
                'caption' => __('inventory.edit_storage'),
                'icon' => 'pencil',
                'authorized' => Auth::user()->can('edit', $storage),
            ],
            'delete' => [
                'url' => route('inventory.storages.destroy', $storage),
                'caption' => __('inventory.delete_storage'),
                'icon' => 'trash',
                'authorized' => Auth::user()->can('delete', $storage),
                'confirmation' => __('inventory.confirm_delete_storage')
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
