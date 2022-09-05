<?php

namespace App\Exports\Accounting;

use App\Models\Accounting\Transaction;
use App\Models\Accounting\Wallet;

class TransactionsExport extends BaseTransactionsExport
{
    private ?string $filter;

    /**
     * @var string[]
     */
    private array $advancedFilter;

    /**
     * @var string|Carbon
     */
    private $dateFrom;

    /**
     * @var string|Carbon
     */
    private $dateTo;

    private ?Wallet $wallet;

    /**
     * @param  string[]  $advancedFilter
     * @param  string|Carbon  $dateFrom
     * @param  string|Carbon  $dateTo
     */
    public function __construct(
        ?Wallet $wallet,
        ?string $filter = null,
        array $advancedFilter = [],
        $dateFrom = null,
        $dateTo = null
    ) {
        $this->wallet = $wallet;
        $this->filter = $filter;
        $this->advancedFilter = $advancedFilter;
        $this->dateFrom = $dateFrom;
        $this->dateTo = $dateTo;
        $this->orientation = 'landscape';
    }

    public function query(): \Illuminate\Database\Eloquent\Builder
    {
        return Transaction::query()
            ->when($this->wallet !== null, fn ($qry) => $qry->forWallet($this->wallet))
            ->forFilter($this->filter)
            ->forAdvancedFilter($this->advancedFilter)
            ->when(
                ! empty($this->dateFrom),
                fn ($qry) => $qry->whereDate('date', '>=', $this->dateFrom)
            )
            ->when(
                ! empty($this->dateTo),
                fn ($qry) => $qry->whereDate('date', '<=', $this->dateTo)
            )
            ->orderBy('date', 'ASC')
            ->orderBy('created_at', 'ASC');
    }

    public function title(): string
    {
        return __('All transactions');
    }
}
