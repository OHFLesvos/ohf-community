<?php

namespace App\Navigation\ContextButtons\Accounting;

use App\Models\Accounting\MoneyTransaction;
use App\Models\Accounting\Supplier;
use App\Navigation\ContextButtons\ContextButtons;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

class SuppliersContextButtons implements ContextButtons
{
    public function getItems(View $view): array
    {
        return [
            'export' => [
                'url' => route('api.accounting.suppliers.export'),
                'caption' => __('app.export'),
                'icon' => 'download',
                'authorized' => Auth::user()->can('viewAny', Supplier::class),
            ],
            'overview' => [
                'url' => route('accounting.index'),
                'caption' => __('app.overview'),
                'icon' => 'money-bill-alt',
                'authorized' => Auth::user()->can('viewAny', MoneyTransaction::class) || Gate::allows('view-accounting-summary'),
            ],
        ];
    }

}
