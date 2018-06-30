<?php

namespace App\Http\Controllers\Storage;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\StorageContainer;
use App\StorageTransaction;
use Illuminate\Support\Facades\DB;

class TransactionController extends Controller
{
    public function index() {
        return view('storage.containers.index', [
            'containers' => StorageContainer::orderBy('name')->get(),
        ]);
    }

    public function show(StorageContainer $container) {
        return view('storage.containers.show', [
            'container' => $container,
        ]);
    }
    
    public function showTransaction(StorageContainer $container, Request $request) {
        return view('storage.containers.transactions.index', [
                'container' => $container,
                'transactions' => StorageTransaction
                    ::where('item', $request->item)
                    ->select('*')
                    ->orderBy('created_at', 'desc')
                    ->paginate(),
            ]);
    }

    public function create(StorageContainer $container) {
        return view('storage.containers.transactions.create', [
            'container' => $container,
            'items' => StorageTransaction
                ::groupBy('item')
                ->select('item')
                ->orderBy('item')
                ->get()
                ->pluck('item')
                ->toArray(),
        ]);
    }

    public function store(StorageContainer $container, Request $request) {
        $transaction = new StorageTransaction();
        $transaction->item = $request->item;
        $transaction->quantity = $request->quantity;
        $container->transactions()->save($transaction);

        return redirect()->route('storage.containers.show', $container)
            ->with('success', __('storage.items_registered'));
    }

    public function remove(StorageContainer $container, Request $request) {
        $transaction = StorageTransaction
            ::where('item', $request->item)
            ->groupBy('item')
            ->select(DB::raw('sum(quantity) as total'), 'item')
            ->having('total', '>=', 1)
            ->firstOrFail();

        return view('storage.containers.transactions.remove', [
            'container' => $container,
            'item' => $transaction->item,
            'total' => $transaction->total,
        ]);
    }

    public function storeRemove(StorageContainer $container, Request $request) {
        $itemTransaction = StorageTransaction
            ::where('item', $request->item)
            ->groupBy('item')
            ->select(DB::raw('sum(quantity) as total'), 'item')
            ->having('total', '>=', $request->quantity)
            ->firstOrFail();

        $transaction = new StorageTransaction();
        $transaction->item = $itemTransaction->item;
        $transaction->quantity = -($request->quantity);
        $container->transactions()->save($transaction);

        return redirect()->route('storage.containers.show', $container)
            ->with('success', __('storage.items_removed'));
    }
}
