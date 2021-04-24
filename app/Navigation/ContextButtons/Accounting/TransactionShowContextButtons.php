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
                'caption' => __('app.edit'),
                'icon' => 'edit',
                'icon_floating' => 'pencil-alt',
                'authorized' => Auth::user()->can('update', $transaction),
            ],
            'delete' => [
                'url' => route('accounting.transactions.destroy', $transaction),
                'caption' => __('app.delete'),
                'icon' => 'trash',
                'authorized' => Auth::user()->can('delete', $transaction),
                'confirmation' => __('app.confirm_delete_transaction'),
            ],
            'back' => [
                'url' => route('accounting.transactions.index', $transaction->wallet),
                'caption' => __('app.close'),
                'icon' => 'times-circle',
                'authorized' => Auth::user()->can('viewAny', MoneyTransaction::class),
            ],
        ];
    }
}
