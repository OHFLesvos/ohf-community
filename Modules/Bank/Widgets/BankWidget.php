<?php

namespace Modules\Bank\Widgets;

use App\Widgets\Widget;

use Modules\Bank\Util\BankStatistics;

use Illuminate\Support\Facades\Gate;

class BankWidget implements Widget
{
    function authorize(): bool
    {
        return Gate::allows('do-bank-withdrawals') || Gate::allows('view-bank-reports');
    }

    function view(): string
    {
        return 'bank::dashboard.widgets.bank';
    }

    function args(): array {
        return [
            'persons' => BankStatistics::getNumberOfPersonsServedToday(),
            'coupons' => BankStatistics::getNumberOfCouponsHandedOut(),
        ];
    }
}