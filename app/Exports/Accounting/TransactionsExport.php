<?php

namespace App\Exports\Accounting;

use App\Exports\PageOrientation;
use App\Models\Accounting\Transaction;
use App\Models\Accounting\Wallet;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;

class TransactionsExport extends BaseTransactionsExport
{
    /**
     * @param  string[]  $advancedFilter
     */
    public function __construct(
        private ?Wallet $wallet,
        private ?string $filter = null,
        private array $advancedFilter = [],
        private string|Carbon|null $dateFrom = null,
        private string|Carbon|null $dateTo = null
    ) {
        $this->orientation = PageOrientation::Landscape;
    }

    public function query(): Builder
    {
        return Transaction::query()
            ->forWallet($this->wallet)
            ->forFilter($this->filter)
            ->forAdvancedFilter($this->advancedFilter)
            ->forDateRange($this->dateFrom, $this->dateTo)
            ->orderBy('date', 'ASC')
            ->orderBy('created_at', 'ASC');
    }

    public function title(): string
    {
        return __('All transactions');
    }
}
