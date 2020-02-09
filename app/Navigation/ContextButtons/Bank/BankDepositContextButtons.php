<?php

namespace App\Navigation\ContextButtons\Bank;

use App\Navigation\ContextButtons\ContextButtons;

use Illuminate\View\View;
use Illuminate\Support\Facades\Gate;

class BankDepositContextButtons implements ContextButtons {

    public function getItems(View $view): array
    {
        return [
            'transactions' => [
                'url' => route('bank.depositTransactions'),
                'caption' => __('app.transactions'),
                'icon' => 'list',
                'authorized' => Gate::allows('do-bank-deposits')
            ],                    
            'report'=> [
                'url' => route('reporting.bank.deposits'),
                'caption' => __('app.report'),
                'icon' => 'chart-line',
                'authorized' => Gate::allows('view-bank-reports')
            ],                    
        ];
    }

}
