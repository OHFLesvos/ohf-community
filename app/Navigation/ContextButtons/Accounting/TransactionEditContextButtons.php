<?php

namespace App\Navigation\ContextButtons\Accounting;

use App\Models\Accounting\Transaction;
use App\Navigation\ContextButtons\ContextButtons;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class TransactionEditContextButtons implements ContextButtons
{
    public function getItems(View $view): array
    {
        $transaction = $view->getData()['transaction'];
        return [
            'delete' => [
                'url' => route('accounting.transactions.destroy', $transaction),
                'caption' => __('Delete'),
                'icon' => 'trash',
                'authorized' => Auth::user()->can('delete', $transaction),
                'confirmation' => __('Do you really want to delete this transaction?'),
            ],
            'back' => [
                'url' => route('accounting.transactions.index', $transaction->wallet),
                'caption' => __('Cancel'),
                'icon' => 'times-circle',
                'authorized' => Auth::user()->can('viewAny', Transaction::class),
            ],
        ];
    }
}
