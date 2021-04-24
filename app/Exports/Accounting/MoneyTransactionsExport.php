<?php

namespace App\Exports\Accounting;

use App\Models\Accounting\MoneyTransaction;
use App\Models\Accounting\Wallet;

class MoneyTransactionsExport extends BaseMoneyTransactionsExport
{
    /**
     * Filter conditions
     *
     * @var array<string>
     */
    private array $filter;

    private Wallet $wallet;

    public function __construct(Wallet $wallet, array $filter = [])
    {
        $this->wallet = $wallet;
        $this->filter = $filter;
        $this->orientation = 'landscape';
    }

    public function query(): \Illuminate\Database\Eloquent\Builder
    {
        return MoneyTransaction::query()
            ->forWallet($this->wallet)
            ->forFilter($this->filter)
            ->orderBy('date', 'ASC')
            ->orderBy('created_at', 'ASC');
    }

    public function title(): string
    {
        return __('app.all_transactions');
    }
}
