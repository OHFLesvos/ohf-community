<?php

namespace Modules\Accounting\Navigation\ContextButtons;

use App\Navigation\ContextButtons\ContextButtons;

use Modules\Accounting\Entities\MoneyTransaction;

use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;

class TransactionShowContextButtons implements ContextButtons {

    public function getItems(View $view): array
    {
        $transaction = $view->getData()['transaction'];
        return [
            'action' => [
                'url' => route('accounting.transactions.edit', $transaction),
                'caption' => __('app.edit'),
                'icon' => 'edit',
                'icon_floating' => 'pencil-alt',
                'authorized' => Auth::user()->can('update', $transaction)
            ],
            'receipt' => [
                'url' => route('accounting.transactions.editReceipt', $transaction),
                'caption' => __('accounting::accounting.receipt'),
                'icon' => 'list-ol',
                'authorized' => Auth::user()->can('update', $transaction),
            ],
            'delete' => [
                'url' => route('accounting.transactions.destroy', $transaction),
                'caption' => __('app.delete'),
                'icon' => 'trash',
                'authorized' => Auth::user()->can('delete', $transaction),
                'confirmation' => __('accounting::accounting.confirm_delete_transaction')
            ],
            'back' => [
                'url' => route('accounting.transactions.index'),
                'caption' => __('app.close'),
                'icon' => 'times-circle',
                'authorized' => Auth::user()->can('list', MoneyTransaction::class)
            ]
        ];
    }

}
