<?php

namespace App\Widgets;

use App\Transaction;
use Carbon\Carbon;
use Illuminate\Support\Facades\Gate;
use App\Http\Controllers\People\Bank\WithdrawalController;

class BankWidget implements Widget
{
    function authorize(): bool
    {
        return Gate::allows('do-bank-withdrawals') || Gate::allows('view-bank-reports');
    }

    function view(): string
    {
        return 'dashboard.widgets.bank';
    }

    function args(): array {
        return [
            'persons' => WithdrawalController::getNumberOfPersonsServedToday(),
            'coupons' => WithdrawalController::getNumberOfCouponsHandedOut(),
        ];
    }
}