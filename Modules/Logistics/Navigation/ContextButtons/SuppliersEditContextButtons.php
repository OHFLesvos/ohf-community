<?php

namespace Modules\Logistics\Navigation\ContextButtons;

use App\Navigation\ContextButtons\ContextButtons;

use Modules\Logistics\Entities\Supplier;

use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;

class SuppliersEditContextButtons implements ContextButtons {

    public function getItems(View $view): array
    {
        $supplier = $view->getData()['supplier'];
        return [
            'delete' => [
                'url' => route('logistics.suppliers.destroy', $supplier),
                'caption' => __('app.delete'),
                'icon' => 'trash',
                'authorized' => Auth::user()->can('delete', $supplier),
                'confirmation' => __('logistics::suppliers.confirm_delete_supplier')
            ],
            'back' => [
                'url' => route('logistics.suppliers.show', $supplier),
                'caption' => __('app.cancel'),
                'icon' => 'times-circle',
                'authorized' => Auth::user()->can('list', Supplier::class)
            ]
        ];
    }

}
