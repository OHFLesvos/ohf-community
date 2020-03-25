<?php

namespace App\Widgets\Bank;

use App\Util\Bank\BankStatisticsProvider;
use App\Widgets\Widget;
use Illuminate\Support\Facades\Gate;

class BankWidget implements Widget
{
    /**
     * Statistics provider
     *
     * @var BankStatisticsProvider
     */
    private $stats;

    public function __construct()
    {
        $this->stats = new BankStatisticsProvider();
    }

    public function authorize(): bool
    {
        return Gate::allows('do-bank-withdrawals') || Gate::allows('view-bank-reports');
    }

    public function view(): string
    {
        return 'bank.dashboard.widgets.bank';
    }

    public function args(): array
    {
        return [
            'persons' => $this->stats->getNumberOfPersonsServed(),
            'coupons' => $this->stats->getNumberOfCouponsHandedOut(),
        ];
    }
}
