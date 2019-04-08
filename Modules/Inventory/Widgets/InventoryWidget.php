<?php

namespace Modules\Inventory\Widgets;

use App\Widgets\Widget;

use Modules\Inventory\Entities\InventoryStorage;

use Illuminate\Support\Facades\Auth;

use Carbon\Carbon;

class InventoryWidget implements Widget
{
    function authorize(): bool
    {
        return Auth::user()->can('list', InventoryStorage::class);
    }

    function view(): string
    {
        return 'inventory::dashboard.widgets.inventory';
    }

    function args(): array {
        return [
            'storages' => InventoryStorage::orderBy('name')->get(),
        ];
    }
}