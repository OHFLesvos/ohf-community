<?php

namespace Modules\Bank\Widgets;

use App\Widgets\Widget;

use Modules\Bank\Http\Controllers\WithdrawalController;

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
            'persons' => WithdrawalController::getNumberOfPersonsServedToday(), // TODO util class
            'coupons' => WithdrawalController::getNumberOfCouponsHandedOut(), // TODO util class
        ];
    }
}