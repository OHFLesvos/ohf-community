<?php

namespace App\Navigation\ContextButtons;

use App\InventoryStorage;

use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;

class InventoryStorageIndexContextButtons implements ContextButtons {

    public function getItems(View $view): array
    {
        return [
            'add' => [
                'url' => route('inventory.storages.create'),
                'caption' => __('inventory.add_storage'),
                'icon' => 'plus-circle',
                'authorized' => Auth::user()->can('create', InventoryStorage::class),
            ],
        ];
    }

}
