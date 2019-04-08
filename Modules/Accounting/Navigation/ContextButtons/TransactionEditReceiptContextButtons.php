<?php

namespace Modules\Accounting\Navigation\ContextButtons;

use App\Navigation\ContextButtons\ContextButtons;

use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;

class TransactionEditReceiptContextButtons implements ContextButtons {

    public function getItems(View $view): array
    {
        $transaction = $view->getData()['transaction'];
        return [
            'back' => [
                'url' => route('accounting.transactions.show', $transaction),
                'caption' => __('app.close'),
                'icon' => 'times-circle',
                'authorized' => Auth::user()->can('view', $transaction)
            ]
        ];
    }

}
