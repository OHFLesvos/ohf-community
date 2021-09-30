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

    private ?string $filter;

    /**
     * Filter conditions
     *
     * @var array<string>
     */
    private array $advancedFilter;

    private ?Wallet $wallet;

    public function __construct(?Wallet $wallet, Carbon $month, ?string $filter = null, ?array $advancedFilter = [])
    {
        $this->wallet = $wallet;
        $this->month = $month;
        $this->filter = $filter;
        $this->advancedFilter = $advancedFilter;
    }

    public function query(): \Illuminate\Database\Eloquent\Builder
    {
        $dateFrom = $this->month;
        $dateTo = (clone $dateFrom)->endOfMonth();

        return Transaction::query()
            ->when($this->wallet !== null, fn ($qry) => $qry->forWallet($this->wallet))
            ->forFilter($this->filter)
            ->forAdvancedFilter($this->advancedFilter)
            ->forDateRange($dateFrom, $dateTo)
            ->orderBy('date', 'ASC')
            ->orderBy('created_at', 'ASC');
    }

    public function title(): string
    {
        return $this->month->formatLocalized('%B %Y');
    }
}
