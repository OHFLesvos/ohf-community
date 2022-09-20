<?php

namespace App\Exports\Accounting\Sheets;

use App\Exports\Accounting\BaseTransactionsExport;
use App\Models\Accounting\Transaction;
use App\Models\Accounting\Wallet;
use Carbon\Carbon;

class TransactionsMonthSheet extends BaseTransactionsExport
{
    /**
     * @param  Wallet|null  $wallet Wallet
     * @param  Carbon  $month Month date
     * @param  string|null  $filter Simple filter
     * @param  array<string>|null  $advancedFilter Filter conditions
     */
    public function __construct(
        private ?Wallet $wallet,
        private Carbon $month,
        private ?string $filter = null,
        private ?array $advancedFilter = [])
    {
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
