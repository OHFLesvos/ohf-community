<?php

namespace App\Exports\Accounting\Sheets;

use App\Exports\Accounting\BaseTransactionsExport;
use App\Models\Accounting\Transaction;
use App\Models\Accounting\Wallet;
use Carbon\Carbon;

class TransactionsMonthSheet extends BaseTransactionsExport
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
    private array $advancedFilter;

    private Wallet $wallet;

    public function __construct(Wallet $wallet, Carbon $month, ?array $advancedFilter = [])
    {
        $this->wallet = $wallet;
        $this->month = $month;
        $this->advancedFilter = $advancedFilter;
    }

    public function query(): \Illuminate\Database\Eloquent\Builder
    {
        $dateFrom = $this->month;
        $dateTo = (clone $dateFrom)->endOfMonth();

        return Transaction::query()
            ->forWallet($this->wallet)
            ->forAdvancedFilter($this->advancedFilter, true)
            ->orderBy('date', 'ASC')
            ->orderBy('created_at', 'ASC')
            ->whereDate('date', '>=', $dateFrom)
            ->whereDate('date', '<=', $dateTo);
    }

    public function title(): string
    {
        return $this->month->formatLocalized('%B %Y');
    }
}
