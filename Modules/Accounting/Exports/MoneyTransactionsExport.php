<?php

namespace Modules\Accounting\Exports;

use Modules\Accounting\Entities\MoneyTransaction;

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
        return __('accounting::accounting.all_transactions');
    }

}
