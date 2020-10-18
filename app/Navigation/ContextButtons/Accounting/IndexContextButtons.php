<?php

namespace App\Navigation\ContextButtons\Accounting;

use App\Navigation\ContextButtons\ContextButtons;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

class IndexContextButtons implements ContextButtons
{
    public function getItems(View $view): array
    {
        return [
            'globalSummary' => [
                'url' => route('accounting.transactions.globalSummary'),
                'caption' => __('accounting.summary'),
                'icon' => 'globe',
                'authorized' => Gate::allows('view-accounting-summary'),
            ],
            'suppliers' => [
                'url' => route('accounting.suppliers'),
                'caption' => __('accounting.suppliers'),
                'icon' => 'truck',
                'authorized' => Auth::user()->can('viewAny', Supplier::class) || Gate::allows('manage-suppliers'),
            ],
        ];
    }

}
