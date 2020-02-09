<?php

namespace App\Navigation\ContextButtons\Bank;

use App\Navigation\ContextButtons\ContextButtons;

use Illuminate\View\View;
use Illuminate\Support\Facades\Gate;

class BankDepositTransactionsContextButtons implements ContextButtons {

    public function getItems(View $view): array
    {
        return [
            'back' => [
                'url' => route('bank.deposit'),
                'caption' => __('app.close'),
                'icon' => 'times-circle',
                'authorized' => Gate::allows('do-bank-deposits')
            ]
        ];
    }

}
