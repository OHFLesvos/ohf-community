<?php

namespace App\Exports\Accounting;

use App\Http\Controllers\Accounting\MoneyTransactionsController;
use App\Models\Accounting\MoneyTransaction;

class MoneyTransactionsExport extends BaseMoneyTransactionsExport
{
    /**
     * Filter conditions
     *
     * @var array<string>
     */
    private array $filter;

    public function __construct(array $filter = [])
    {
        $this->filter = $filter;
        $this->orientation = 'landscape';
    }

    public function query(): \Illuminate\Database\Eloquent\Builder
    {
        $qry = MoneyTransaction::orderBy('date', 'ASC')
            ->orderBy('created_at', 'ASC');
        MoneyTransactionsController::applyFilterToQuery($this->filter, $qry);
        return $qry;
    }

    public function title(): string
    {
        return __('accounting.all_transactions');
    }

}
