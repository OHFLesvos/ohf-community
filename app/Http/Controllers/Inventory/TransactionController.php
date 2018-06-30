<?php

namespace App\Http\Controllers\Inventory;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\InventoryStorage;
use App\InventoryItemTransaction;
use Illuminate\Support\Facades\DB;

class TransactionController extends Controller
{
    public function index() {
        return view('inventory.storages.index', [
            'storages' => InventoryStorage::orderBy('name')->get(),
        ]);
    }

    public function show(InventoryStorage $storage) {
        return view('inventory.storages.show', [
            'storage' => $storage,
        ]);
    }
    
    public function showTransaction(InventoryStorage $storage, Request $request) {
        return view('inventory.storages.transactions.index', [
                'storage' => $storage,
                'transactions' => InventoryItemTransaction
                    ::where('item', $request->item)
                    ->select('*')
                    ->orderBy('created_at', 'desc')
                    ->paginate(),
            ]);
    }

    public function create(InventoryStorage $storage) {
        return view('inventory.storages.transactions.create', [
            'storage' => $storage,
            'items' => InventoryItemTransaction
                ::groupBy('item')
                ->select('item')
                ->orderBy('item')
                ->get()
                ->pluck('item')
                ->toArray(),
        ]);
    }

    public function store(InventoryStorage $storage, Request $request) {
        $transaction = new InventoryItemTransaction();
        $transaction->item = $request->item;
        $transaction->quantity = $request->quantity;
        $storage->transactions()->save($transaction);

        return redirect()->route('inventory.storages.show', $storage)
            ->with('success', __('inventory.items_registered'));
    }

    public function remove(InventoryStorage $storage, Request $request) {
        $transaction = InventoryItemTransaction
            ::where('item', $request->item)
            ->groupBy('item')
            ->select(DB::raw('sum(quantity) as total'), 'item')
            ->having('total', '>=', 1)
            ->firstOrFail();

        return view('inventory.storages.transactions.remove', [
            'storage' => $storage,
            'item' => $transaction->item,
            'total' => $transaction->total,
        ]);
    }

    public function storeRemove(InventoryStorage $storage, Request $request) {
        $itemTransaction = InventoryItemTransaction
            ::where('item', $request->item)
            ->groupBy('item')
            ->select(DB::raw('sum(quantity) as total'), 'item')
            ->having('total', '>=', $request->quantity)
            ->firstOrFail();

        $transaction = new InventoryItemTransaction();
        $transaction->item = $itemTransaction->item;
        $transaction->quantity = -($request->quantity);
        $storage->transactions()->save($transaction);

        return redirect()->route('inventory.storages.show', $storage)
            ->with('success', __('inventory.items_removed'));
    }
}
