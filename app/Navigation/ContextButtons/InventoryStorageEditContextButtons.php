<?php

namespace App\Navigation\ContextButtons;

use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;

class InventoryStorageEditContextButtons implements ContextButtons {

    public function getItems(View $view): array
    {
        $storage = $view->getData()['storage'];
        return [
            'back' => [
                'url' => route('inventory.storages.show', $storage),
                'caption' => __('app.cancel'),
                'icon' => 'times-circle',
                'authorized' => Auth::user()->can('view', $storage),
            ],
        ];
    }

}
