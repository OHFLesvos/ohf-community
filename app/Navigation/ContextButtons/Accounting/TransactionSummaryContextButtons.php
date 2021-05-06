<?php

namespace App\Navigation\ContextButtons\Accounting;

use App\Models\Accounting\MoneyTransaction;
use App\Navigation\ContextButtons\ContextButtons;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

class TransactionSummaryContextButtons implements ContextButtons
{
    public function getItems(View $view): array
    {
        return [
            'close' => [
                'url' => route('accounting.index'),
                'caption' => __('Close'),
                'icon' => 'times-circle',
                'authorized' => Auth::user()->can('viewAny', MoneyTransaction::class) || Gate::allows('view-accounting-summary'),
            ]
        ];
    }
}
