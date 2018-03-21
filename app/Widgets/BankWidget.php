<?php

namespace App\Widgets;

use App\Transaction;
use Carbon\Carbon;
use Illuminate\Support\Facades\Gate;

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
            'persons' => \App\Http\Controllers\BankController::getNumberOfPersonsServedToday(),
            'coupons' => \App\Http\Controllers\BankController::getNumberOfCouponsHandedOut(),
        ];
    }
}