<?php

namespace Modules\Accounting\Widgets;

use App\Widgets\Widget;

use Modules\Accounting\Entities\MoneyTransaction;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\DB;

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

    function args(): array {
        // TODO: Probably define on more general location
        setlocale(LC_TIME, \App::getLocale());

        $dateFrom = Carbon::now()->startOfMonth();
        $dateTo = (clone $dateFrom)->endOfMonth();
        return [
            'monthName' => $dateFrom->formatLocalized('%B %Y'),
            'income' => MoneyTransaction
                ::select(DB::raw('SUM(amount) as sum'))
                ->whereDate('date', '>=', $dateFrom)
                ->whereDate('date', '<=', $dateTo)
                ->where('type', 'income')
                ->get()
                ->first()
                ->sum,
            'spending' => MoneyTransaction
                ::select(DB::raw('SUM(amount) as sum'))
                ->whereDate('date', '>=', $dateFrom)
                ->whereDate('date', '<=', $dateTo)
                ->where('type', 'spending')
                ->get()
                ->first()
                ->sum,
        ];
    }
}