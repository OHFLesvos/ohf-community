<?php

namespace App\Navigation\ContextButtons\Accounting;

use App\Models\Accounting\MoneyTransaction;
use App\Navigation\ContextButtons\ContextButtons;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class TransactionShowContextButtons implements ContextButtons
{
    public function getItems(View $view): array
    {
        $transaction = $view->getData()['transaction'];
        return [
            'action' => [
                'url' => route('accounting.transactions.edit', $transaction),
                'caption' => __('Edit'),
                'icon' => 'edit',
                'icon_floating' => 'pencil-alt',
                'authorized' => Auth::user()->can('update', $transaction),
            ],
            'delete' => [
                'url' => route('accounting.transactions.destroy', $transaction),
                'caption' => __('Delete'),
                'icon' => 'trash',
                'authorized' => Auth::user()->can('delete', $transaction),
                'confirmation' => __('Do you really want to delete this transaction?'),
            ],
            'back' => [
                'url' => route('accounting.transactions.index', $transaction->wallet),
                'caption' => __('Close'),
                'icon' => 'times-circle',
                'authorized' => Auth::user()->can('viewAny', MoneyTransaction::class),
            ],
        ];
    }
}
