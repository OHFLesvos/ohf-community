<?php

namespace App\Navigation\ContextButtons\Accounting;

use App\Models\Accounting\MoneyTransaction;
use App\Navigation\ContextButtons\ContextButtons;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class TransactionSummaryContextButtons implements ContextButtons
{
    public function getItems(View $view): array
    {
        return [
            'export' => [
                'url' => route('accounting.transactions.export'),
                'caption' => __('app.export'),
                'icon' => 'download',
                'authorized' => Auth::user()->can('list', MoneyTransaction::class),
            ],
            'book' => [
                'url' => route('accounting.webling.index'),
                'caption' => __('accounting.book'),
                'icon' => 'list-alt',
                'authorized' => Auth::user()->can('book-accounting-transactions-externally'),
            ],
        ];
    }

}
