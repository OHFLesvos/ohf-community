<?php

namespace Modules\Inventory\Navigation\ContextButtons;

use App\Navigation\ContextButtons\ContextButtons;

use Modules\Inventory\Entities\InventoryItemTransaction;

use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\DB;

class InventoryTransactionChangesContextButtons implements ContextButtons {

    public function getItems(View $view): array
    {
        $storage = $view->getData()['storage'];
        $numTransactions = InventoryItemTransaction::where('item', request()->item)->count();
        $sum = InventoryItemTransaction::where('item', request()->item)->groupBy('item')->select(DB::raw('SUM(quantity) as sum'), 'item')->orderBy('item')->first()->sum;
        return [
            'add' => [
                'url' => route('inventory.transactions.ingress', $storage) . '?item=' . request()->item,
                'caption' => __('inventory::inventory.store_items'),
                'icon' => 'plus-circle',
                'authorized' => $numTransactions > 0 && Auth::user()->can('create', InventoryItemTransaction::class),
            ],
            'remove' => [
                'url' => route('inventory.transactions.egress', $storage) . '?item=' . request()->item,
                'caption' => __('inventory::inventory.take_out_items'),
                'icon' => 'minus-circle',
                'authorized' => $numTransactions > 0 && $sum > 0 && Auth::user()->can('create', InventoryItemTransaction::class),
            ],
            'delete' => [
                'url' => route('inventory.transactions.destroy', $storage) . '?item=' . request()->item,
                'caption' => __('app.delete'),
                'icon' => 'trash',
                'authorized' => $numTransactions > 0 && Auth::user()->can('delete', InventoryItemTransaction::class),
                'confirmation' => __('inventory::inventory.confirm_delete_item')
            ],
            'back' => [
                'url' => route('inventory.storages.show', $storage),
                'caption' => __('app.close'),
                'icon' => 'times-circle',
                'authorized' => Auth::user()->can('view', $storage),
            ]
        ];
    }

}
