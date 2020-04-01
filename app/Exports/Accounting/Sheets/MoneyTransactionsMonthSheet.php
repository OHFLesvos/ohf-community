<?php

namespace App\Exports\Accounting\Sheets;

use App\Exports\Accounting\BaseMoneyTransactionsExport;
use App\Http\Controllers\Accounting\MoneyTransactionsController;
use App\Models\Accounting\MoneyTransaction;
use Carbon\Carbon;

class MoneyTransactionsMonthSheet extends BaseMoneyTransactionsExport
{
    /**
     * Month date
     */
    private Carbon $month;

    /**
     * Filter conditions
     *
     * @var array<string>
     */
    private array $filter;

    public function __construct(Carbon $month, ?array $filter = [])
    {
        $this->month = $month;
        $this->filter = $filter;
    }

    public function query(): \Illuminate\Database\Eloquent\Builder
    {
        $dateFrom = $this->month;
        $dateTo = (clone $dateFrom)->endOfMonth();

        $qry = MoneyTransaction::orderBy('date', 'ASC')
            ->orderBy('created_at', 'ASC')
            ->whereDate('date', '>=', $dateFrom)
            ->whereDate('date', '<=', $dateTo);
        MoneyTransactionsController::applyFilterToQuery($this->filter, $qry, true);
        return $qry;
    }

    public function title(): string
    {
        return $this->month->formatLocalized('%B %Y');
    }

}
