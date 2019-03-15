<?php

namespace Modules\Accounting\Navigation\ContextButtons;

use App\Navigation\ContextButtons\ContextButtons;

use Modules\Accounting\Entities\MoneyTransaction;

use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;

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
        ];
    }

}
