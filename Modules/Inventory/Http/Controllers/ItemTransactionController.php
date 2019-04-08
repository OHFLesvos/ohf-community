<?php

namespace Modules\Inventory\Http\Controllers;

use App\Http\Controllers\Controller;

use Modules\Inventory\Entities\InventoryStorage;
use Modules\Inventory\Entities\InventoryItemTransaction;
use Modules\Inventory\Http\Requests\StoreIngressTransaction;
use Modules\Inventory\Http\Requests\StoreEgressTransaction;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ItemTransactionController extends Controller
{
    public function changes(InventoryStorage $storage, Request $request) {
        $this->authorize('list', InventoryItemTransaction::class);

        $request->validate([
            'item' => 'required',
        ]);

        $transactions = InventoryItemTransaction
            ::where('item', $request->item)
            ->select('*')
            ->orderBy('created_at', 'desc');

        return view('inventory::transactions.changes', [
                'storage' => $storage,
                'transactions' => $transactions->paginate(),
            ]);
    }

    public function ingress(InventoryStorage $storage) {
        $this->authorize('create', InventoryItemTransaction::class);

        return view('inventory::transactions.ingress', [
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
        $this->authorize('create', InventoryItemTransaction::class);

        foreach($request->item as $k => $v) {
            $transaction = new InventoryItemTransaction();
            $transaction->item = $request->item[$k];
            $transaction->quantity = $request->quantity[$k];
            $transaction->origin = $request->origin;
            $transaction->sponsor = $request->sponsor;
            $storage->transactions()->save($transaction);
        }

        return redirect()->route('inventory.storages.show', $storage)
            ->with('success', __('inventory::inventory.items_stored'));
    }

    public function egress(InventoryStorage $storage, Request $request) {
        $this->authorize('create', InventoryItemTransaction::class);

        $request->validate([
            'item' => 'required',
        ]);

        $transaction = InventoryItemTransaction
            ::where('item', $request->item)
            ->groupBy('item')
            ->select(DB::raw('sum(quantity) as total'), 'item')
            ->having('total', '>=', 1)
            ->firstOrFail();

        return view('inventory::transactions.egress', [
            'storage' => $storage,
            'item' => $transaction->item,
            'total' => $transaction->total,
            'items' => InventoryItemTransaction
                ::groupBy('item')
                ->select('item')
                ->orderBy('item')
                ->get()
                ->pluck('item')
                ->toArray(),
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
        $this->authorize('create', InventoryItemTransaction::class);

        foreach($request->item as $k => $v) {
            $itemTransaction = InventoryItemTransaction
                ::where('item', $request->item[$k])
                ->groupBy('item')
                ->select(DB::raw('sum(quantity) as total'), 'item')
                ->having('total', '>=', $request->quantity[$k])
                ->first();
            if ($itemTransaction != null) {
                $transaction = new InventoryItemTransaction();
                $transaction->item = $itemTransaction->item;
                $transaction->quantity = -($request->quantity[$k]);
                $transaction->destination = $request->destination;
                $storage->transactions()->save($transaction);
            }
            // TODO Storage validation
        }

        return redirect()->route('inventory.storages.show', $storage)
            ->with('success', __('inventory::inventory.items_taken_out'));
    }

    public function destroy(InventoryStorage $storage, Request $request) {
        $this->authorize('delete', InventoryItemTransaction::class);

        $request->validate([
            'item' => 'required',
        ]);

        InventoryItemTransaction
            ::where('item', $request->item)
            ->delete();

        return redirect()->route('inventory.storages.show', $storage)
            ->with('success', __('inventory::inventory.item_deleted'));
    }

}
