<?php

namespace App\Exports\Accounting;

use App\Models\Accounting\Budget;

class BudgetTransactionsExport extends BaseTransactionsExport
{
    private Budget $budget;

    public function __construct(
        Budget $budget
    ) {
        $this->budget = $budget;
        $this->orientation = 'landscape';
    }

    public function query(): \Illuminate\Database\Eloquent\Builder
    {
        return $this->budget->transactions()
            ->getQuery()
            ->orderBy('date', 'ASC')
            ->orderBy('created_at', 'ASC');
    }

    public function title(): string
    {
        return $this->budget->name . ' ' . __('Transactions');
    }
}
