<?php

namespace App\Exports;

use App\MoneyTransaction;

use Illuminate\Support\Collection;

class MoneyTransactionsExport extends BaseMoneyTransactionsExport
{
    public function __construct()
    {
        $this->setOrientation('landscape');
    }    

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection(): Collection
    {
        return MoneyTransaction
                ::orderBy('date', 'ASC')
                ->orderBy('created_at', 'ASC')
                ->get();
    }

    /**
     * @return string
     */
    public function title(): string
    {
        return __('accounting.all_transactions');
    }

}
