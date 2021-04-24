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
        $wallet = $view->getData()['wallet'];
        return [
            'globalSummary' => [
                'url' => route('accounting.transactions.globalSummary'),
                'caption' => __('app.global_summary'),
                'icon' => 'globe',
                'authorized' => Gate::allows('view-accounting-summary'),
            ],
            'back' => [
                'url' => route('accounting.transactions.index', $wallet),
                'caption' => __('app.close'),
                'icon' => 'times-circle',
                'authorized' => Auth::user()->can('viewAny', MoneyTransaction::class),
            ],
        ];
    }
}
