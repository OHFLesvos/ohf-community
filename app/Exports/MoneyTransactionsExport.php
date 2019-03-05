<?php

namespace App\Exports;

use App\MoneyTransaction;

class MoneyTransactionsExport extends BaseMoneyTransactionsExport
{
    public function __construct()
    {
        $this->setOrientation('landscape');
    }    

    public function query(): \Illuminate\Database\Eloquent\Builder
    {
        return MoneyTransaction
                ::orderBy('date', 'ASC')
                ->orderBy('created_at', 'ASC');
    }

    /**
     * @return string
     */
    public function title(): string
    {
        return __('accounting.all_transactions');
    }

}
