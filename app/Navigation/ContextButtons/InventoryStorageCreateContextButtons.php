<?php

namespace App\Navigation\ContextButtons;

use App\InventoryStorage;

use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;

class InventoryStorageCreateContextButtons implements ContextButtons {

    public function getItems(View $view): array
    {
        return [
            'back' => [
                'url' => route('inventory.storages.index'),
                'caption' => __('app.cancel'),
                'icon' => 'times-circle',
                'authorized' => Auth::user()->can('list', InventoryStorage::class),
            ],
        ];
    }

}
