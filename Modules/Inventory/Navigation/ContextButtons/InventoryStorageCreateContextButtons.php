<?php

namespace Modules\Inventory\Navigation\ContextButtons;

use App\Navigation\ContextButtons\ContextButtons;

use Modules\Inventory\Entities\InventoryStorage;

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