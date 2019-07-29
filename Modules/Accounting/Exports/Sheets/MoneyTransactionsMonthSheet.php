<?php

namespace Modules\Accounting\Exports\Sheets;

use Modules\Accounting\Entities\MoneyTransaction;
use Modules\Accounting\Exports\BaseMoneyTransactionsExport;
use Modules\Accounting\Http\Controllers\MoneyTransactionsController;

class MoneyTransactionsMonthSheet extends BaseMoneyTransactionsExport
{
    private $month;
    private $filter;

    public function __construct($month, $filter = [])
    {
        $this->month = $month;
        $this->filter = $filter;
    }

    public function query(): \Illuminate\Database\Eloquent\Builder
    {
        $dateFrom = $this->month;
        $dateTo = (clone $dateFrom)->endOfMonth();

        $qry = MoneyTransaction
            ::orderBy('date', 'ASC')
            ->orderBy('created_at', 'ASC')
            ->whereDate('date', '>=', $dateFrom)
            ->whereDate('date', '<=', $dateTo);
        MoneyTransactionsController::applyFilterToQuery($this->filter, $qry, true);
        return $qry;
    }

    /**
     * @return string
     */
    public function title(): string
    {
        return $this->month->formatLocalized('%B %Y');
    }

}
