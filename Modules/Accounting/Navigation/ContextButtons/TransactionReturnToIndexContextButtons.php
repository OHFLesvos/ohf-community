<?php

namespace Modules\Accounting\Navigation\ContextButtons;

use App\Navigation\ContextButtons\ContextButtons;

use Modules\Accounting\Entities\MoneyTransaction;

use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;

class TransactionReturnToIndexContextButtons implements ContextButtons {

    public function getItems(View $view): array
    {
        return [
            'back' => [
                'url' => route('accounting.transactions.index'),
                'caption' => __('app.cancel'),
                'icon' => 'times-circle',
                'authorized' => Auth::user()->can('list', MoneyTransaction::class)
            ]
        ];
    }

}
