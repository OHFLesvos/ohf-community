<?php

namespace App\Exports\Accounting;

use App\Models\Accounting\MoneyTransaction;
use App\Http\Controllers\Accounting\MoneyTransactionsController;

class MoneyTransactionsExport extends BaseMoneyTransactionsExport
{
    private $filter;

    public function __construct($filter = [])
    {
        $this->filter = $filter;
        $this->setOrientation('landscape');
    }

    public function query(): \Illuminate\Database\Eloquent\Builder
    {
        $qry = MoneyTransaction
                ::orderBy('date', 'ASC')
                ->orderBy('created_at', 'ASC');
        MoneyTransactionsController::applyFilterToQuery($this->filter, $qry);
        return $qry;
    }

    /**
     * @return string
     */
    public function title(): string
    {
        return __('accounting.all_transactions');
    }

}
