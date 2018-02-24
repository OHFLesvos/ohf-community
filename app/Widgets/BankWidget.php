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
            'num_transactions_today' => Transaction::whereDate('created_at', '=', Carbon::today())->where('transactionable_type', 'App\Person')->count(),
            'num_people_served_today' => \App\Http\Controllers\BankController::getNumberOfPersonsServedToday(),
            'transaction_value_today' => \App\Http\Controllers\BankController::getTransactionValueToday(),
        ];
    }
}