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
            'globalSummary' => $wallet != null ? [
                'url' => route('accounting.transactions.summary'),
                'caption' => __('All wallets'),
                'icon' => 'globe',
                'authorized' => Gate::allows('view-accounting-summary'),
            ] : null,
            'close' => $wallet == null ? [
                'url' => route('accounting.index'),
                'caption' => __('Close'),
                'icon' => 'times-circle',
                'authorized' => Auth::user()->can('viewAny', MoneyTransaction::class) || Gate::allows('view-accounting-summary'),
            ] : null,
            'back' => $wallet != null ? [
                'url' => route('accounting.transactions.index', $wallet),
                'caption' => __('Close'),
                'icon' => 'times-circle',
                'authorized' => Auth::user()->can('viewAny', MoneyTransaction::class),
            ] : null,
        ];
    }
}
