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
            'vcard' => (isset($supplier->phone) || isset($supplier->email)) ? [
                'url' => route('logistics.suppliers.vcard', $supplier),
                'caption' => __('app.vcard'),
                'icon' => 'address-card',
                'authorized' => Auth::user()->can('view', $supplier)
            ] : null,
            'back' => [
                'url' => route('logistics.suppliers.index'),
                'caption' => __('app.close'),
                'icon' => 'times-circle',
                'authorized' => Auth::user()->can('list', Supplier::class)
            ]
        ];
    }

}
