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
        return view('inventory.storages.index', [
            'storages' => InventoryStorage
                ::orderBy('name')
                ->get(),
        ]);
    }

    public function show(InventoryStorage $storage) {
        return view('inventory.storages.show', [
            'storage' => $storage,
        ]);
    }

}
