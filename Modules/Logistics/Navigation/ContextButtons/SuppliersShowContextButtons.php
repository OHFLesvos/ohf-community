<?php

namespace Modules\Logistics\Navigation\ContextButtons;

use App\Navigation\ContextButtons\ContextButtons;

use Modules\Logistics\Entities\Supplier;

use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;

class SuppliersShowContextButtons implements ContextButtons {

    public function getItems(View $view): array
    {
        $supplier = $view->getData()['supplier'];
        return [
            'action' => [
                'url' => route('logistics.suppliers.edit', $supplier),
                'caption' => __('app.edit'),
                'icon' => 'edit',
                'icon_floating' => 'pencil-alt',
                'authorized' => Auth::user()->can('update', $supplier)
            ],
            'back' => [
                'url' => route('logistics.suppliers.index'),
                'caption' => __('app.cancel'),
                'icon' => 'times-circle',
                'authorized' => Auth::user()->can('list', Supplier::class)
            ]
        ];
    }

}
