<?php

namespace Modules\Accounting\Exports;

use Modules\Accounting\Entities\MoneyTransaction;
use Modules\Accounting\Http\Controllers\MoneyTransactionsController;

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
        return __('accounting::accounting.all_transactions');
    }

}
