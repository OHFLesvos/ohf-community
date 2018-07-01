<?php

namespace App\Widgets;

use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use App\InventoryStorage;

class InventoryWidget implements Widget
{
    function authorize(): bool
    {
        return Auth::user()->can('list', InventoryStorage::class);
    }

    function view(): string
    {
        return 'dashboard.widgets.inventory';
    }

    function args(): array {
        return [
            'storages' => InventoryStorage::orderBy('name')->get(),
        ];
    }
}