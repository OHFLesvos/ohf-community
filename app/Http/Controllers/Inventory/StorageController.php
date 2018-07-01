<?php

namespace App\Http\Controllers\Inventory;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\InventoryStorage;
use App\InventoryItemTransaction;
use Illuminate\Support\Facades\DB;

class StorageController extends Controller
{
    public function index() {
        $this->authorize('list', InventoryStorage::class);

        return view('inventory.storages.index', [
            'storages' => InventoryStorage
                ::orderBy('name')
                ->get(),
        ]);
    }
    
    public function create() {
        $this->authorize('create', InventoryStorage::class);

        return view('inventory.storages.create', [
        ]);
    }

    public function store(Request $request) {
        $this->authorize('create', InventoryStorage::class);

        $storage = new InventoryStorage();
        $storage->name = $request->name;
        $storage->description = $request->description;
        $storage->save();

        return redirect()->route('inventory.storages.show', $storage)
            ->with('success', __('inventory.storage_added'));
    }

    public function show(InventoryStorage $storage) {
        $this->authorize('view', $storage);

        return view('inventory.storages.show', [
            'storage' => $storage,
        ]);
    }

    public function edit(InventoryStorage $storage) {
        $this->authorize('update', $storage);

        return view('inventory.storages.edit', [
            'storage' => $storage,
        ]);
    }

    public function update(InventoryStorage $storage, Request $request) {
        $this->authorize('update', $storage);

        $storage->name = $request->name;
        $storage->description = $request->description;
        $storage->save();

        return redirect()->route('inventory.storages.show', $storage)
            ->with('success', __('inventory.storage_updated'));
    }

    public function destroy(InventoryStorage $storage) {
        $this->authorize('delete', $storage);
        
        $storage->delete();

        return redirect()->route('inventory.storages.index')
            ->with('success', __('inventory.storage_deleted'));
    }

}
