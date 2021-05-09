<?php

namespace App\Navigation\ContextButtons\Accounting;

use App\Models\Accounting\Supplier;
use App\Navigation\ContextButtons\ContextButtons;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class SuppliersContextButtons implements ContextButtons
{
    public function getItems(View $view): array
    {
        return [
            'export' => [
                'url' => route('api.accounting.suppliers.export'),
                'caption' => __('Export'),
                'icon' => 'download',
                'authorized' => Auth::user()->can('viewAny', Supplier::class),
            ],
        ];
    }
}
