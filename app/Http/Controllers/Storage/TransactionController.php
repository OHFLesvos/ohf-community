<?php

namespace App\Http\Controllers\Storage;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\StorageContainer;
use App\StorageStorageTransaction;

class TransactionController extends Controller
{
    public function overview() {
        return view('storage.overview', [
            'containers' => StorageContainer::orderBy('name')->get(),
        ]);
    }
}
