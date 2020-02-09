<?php

namespace App\Navigation\ContextButtons\Accounting;

use App\Navigation\ContextButtons\ContextButtons;

use App\Models\Accounting\MoneyTransaction;

use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class TransactionSummaryContextButtons implements ContextButtons {

    public function getItems(View $view): array
    {
        return [
            'export' => [
                'url' => route('accounting.transactions.export'),
                'caption' => __('app.export'),
                'icon' => 'download',
                'authorized' => Auth::user()->can('list', MoneyTransaction::class)
            ],
            'settings' => [
                'url' => route('accounting.settings.edit'),
                'caption' => __('app.settings'),
                'icon' => 'cogs',
                'authorized' => Gate::allows('configure-accounting')
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
