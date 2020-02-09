<?php

namespace App\Widgets\Bank;

use App\Widgets\Widget;

use App\Util\Bank\BankStatisticsProvider;

use Illuminate\Support\Facades\Gate;

class BankWidget implements Widget
{
    private $stats;

    function __construct()
    {
        $this->stats = new BankStatisticsProvider();
    }

    function authorize(): bool
    {
        return Gate::allows('do-bank-withdrawals') || Gate::allows('view-bank-reports');
    }

    function view(): string
    {
        return 'bank.dashboard.widgets.bank';
    }

    function args(): array {
        return [
            'persons' => $this->stats->getNumberOfPersonsServed(),
            'coupons' => $this->stats->getNumberOfCouponsHandedOut(),
        ];
    }
}