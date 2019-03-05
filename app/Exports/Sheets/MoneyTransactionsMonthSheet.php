<?php

namespace App\Exports\Sheets;

use App\MoneyTransaction;
use App\Exports\BaseMoneyTransactionsExport;

use Illuminate\Support\Collection;

class MoneyTransactionsMonthSheet extends BaseMoneyTransactionsExport
{
    private $month;

    public function __construct($month)
    {
        $this->month = $month;
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection(): Collection
    {
        $dateFrom = $this->month;
        $dateTo = (clone $dateFrom)->endOfMonth();

        return MoneyTransaction
            ::orderBy('date', 'ASC')
            ->orderBy('created_at', 'ASC')
            ->whereDate('date', '>=', $dateFrom)
            ->whereDate('date', '<=', $dateTo)                        
            ->get();
    }

    /**
     * @return string
     */
    public function title(): string
    {
        return $this->month->formatLocalized('%B %Y');
    }

}
