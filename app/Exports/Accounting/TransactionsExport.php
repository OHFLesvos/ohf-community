<?php

namespace App\Exports\Accounting;

use App\Models\Accounting\Transaction;
use App\Models\Accounting\Wallet;

class TransactionsExport extends BaseTransactionsExport
{
    private ?string $filter;

    /**
     * Filter conditions
     *
     * @var array<string>
     */
    private array $advancedFilter;

    private Wallet $wallet;

    public function __construct(Wallet $wallet, ?string $filter = null, array $advancedFilter = [])
    {
        $this->wallet = $wallet;
        $this->filter = $filter;
        $this->advancedFilter = $advancedFilter;
        $this->orientation = 'landscape';
    }

    public function query(): \Illuminate\Database\Eloquent\Builder
    {
        return Transaction::query()
            ->forWallet($this->wallet)
            ->forFilter($this->filter)
            ->forAdvancedFilter($this->advancedFilter)
            ->orderBy('date', 'ASC')
            ->orderBy('created_at', 'ASC');
    }

    public function title(): string
    {
        return __('All transactions');
    }
}
