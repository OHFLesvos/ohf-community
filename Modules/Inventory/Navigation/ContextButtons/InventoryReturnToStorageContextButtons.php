<?php

namespace Modules\Inventory\Navigation\ContextButtons;

use App\Navigation\ContextButtons\ContextButtons;

use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;

class InventoryReturnToStorageContextButtons implements ContextButtons {

    public function getItems(View $view): array
    {
        $storage = $view->getData()['storage'];
        return [
            'back' => [
                'url' => route('inventory.storages.show', $storage),
                'caption' => __('app.cancel'),
                'icon' => 'times-circle',
                'authorized' => Auth::user()->can('view', $storage),
            ]
        ];
    }

}
