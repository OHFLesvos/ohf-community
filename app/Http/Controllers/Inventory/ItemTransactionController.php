<?php

namespace App\Http\Controllers\Inventory;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\InventoryStorage;
use App\InventoryItemTransaction;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\Inventory\StoreIngressTransaction;
use App\Http\Requests\Inventory\StoreEgressTransaction;

class ItemTransactionController extends Controller
{
    public function changes(InventoryStorage $storage, Request $request) {
        // TODO Storage auth

        $request->validate([
            'item' => 'required',
        ]);

        $transactions = InventoryItemTransaction
            ::where('item', $request->item)
            ->select('*')
            ->orderBy('created_at', 'desc');

        return view('inventory.transactions.changes', [
                'storage' => $storage,
                'transactions' => $transactions->paginate(),
            ]);
    }

    public function ingress(InventoryStorage $storage) {
        // TODO Storage auth

        return view('inventory.transactions.ingress', [
            'storage' => $storage,
            'items' => InventoryItemTransaction
                ::groupBy('item')
                ->select('item')
                ->orderBy('item')
                ->get()
                ->pluck('item')
                ->toArray(),
            'origins' => InventoryItemTransaction
                ::groupBy('origin')
                ->whereNotNull('origin')
                ->select('origin')
                ->orderBy('origin')
                ->get()
                ->pluck('origin')
                ->toArray(),
            'payers' => InventoryItemTransaction
                ::groupBy('sponsor')
                ->whereNotNull('sponsor')
                ->select('sponsor')
                ->orderBy('sponsor')
                ->get()
                ->pluck('sponsor')
                ->toArray(),
        ]);
    }

    public function storeIngress(InventoryStorage $storage, StoreIngressTransaction $request) {
        // TODO Storage auth

        foreach($request->item as $k => $v) {
            $transaction = new InventoryItemTransaction();
            $transaction->item = $request->item[$k];
            $transaction->quantity = $request->quantity[$k];
            $transaction->origin = $request->origin;
            $transaction->sponsor = $request->sponsor;
            $storage->transactions()->save($transaction);
        }

        return redirect()->route('inventory.storages.show', $storage)
            ->with('success', __('inventory.items_stored'));
    }

    public function egress(InventoryStorage $storage, Request $request) {
        // TODO Storage auth

        $request->validate([
            'item' => 'required',
        ]);

        $transaction = InventoryItemTransaction
            ::where('item', $request->item)
            ->groupBy('item')
            ->select(DB::raw('sum(quantity) as total'), 'item')
            ->having('total', '>=', 1)
            ->firstOrFail();

        return view('inventory.transactions.egress', [
            'storage' => $storage,
            'item' => $transaction->item,
            'total' => $transaction->total,
            'destinations' => InventoryItemTransaction
                ::groupBy('destination')
                ->whereNotNull('destination')
                ->select('destination')
                ->orderBy('destination')
                ->get()
                ->pluck('destination')
                ->toArray(),
        ]);
    }

    public function storeEgress(InventoryStorage $storage, StoreEgressTransaction $request) {
        // TODO Storage auth

        $itemTransaction = InventoryItemTransaction
            ::where('item', $request->item)
            ->groupBy('item')
            ->select(DB::raw('sum(quantity) as total'), 'item')
            ->having('total', '>=', $request->quantity)
            ->firstOrFail();

        $transaction = new InventoryItemTransaction();
        $transaction->item = $itemTransaction->item;
        $transaction->quantity = -($request->quantity);
        $transaction->destination = $request->destination;
        $storage->transactions()->save($transaction);

        return redirect()->route('inventory.storages.show', $storage)
            ->with('success', __('inventory.items_taken_out'));
    }

    public function destroy(InventoryStorage $storage, Request $request) {
        // TODO Storage auth

        $request->validate([
            'item' => 'required',
        ]);

        InventoryItemTransaction
            ::where('item', $request->item)
            ->delete();

        return redirect()->route('inventory.storages.show', $storage)
            ->with('success', __('inventory.item_deleted'));
    }

}
