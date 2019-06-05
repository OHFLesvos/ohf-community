<?php

namespace Modules\Accounting\Widgets;

use App\Widgets\Widget;

use Modules\Accounting\Entities\MoneyTransaction;

use Illuminate\Support\Facades\Gate;

use Carbon\Carbon;

class TransactionsWidget implements Widget
{
    function authorize(): bool
    {
        return Gate::allows('view-accounting-summary');
    }

    function view(): string
    {
        return 'accounting::dashboard.widgets.transactions';
    }

    function args(): array
    {
        return [
            'wallet' => MoneyTransaction::currentWallet(),
        ];
    }
}