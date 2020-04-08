<?php

namespace App\Exports\Accounting;

use App\Models\Accounting\MoneyTransaction;
use App\Services\Accounting\CurrentWalletService;

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
        return MoneyTransaction::query()
            ->forWallet(resolve(CurrentWalletService::class)->get())
            ->forFilter($this->filter)
            ->orderBy('date', 'ASC')
            ->orderBy('created_at', 'ASC');
    }

    public function title(): string
    {
        return __('accounting.all_transactions');
    }

}
