<?php

namespace App\Widgets\Accounting;

use App\Models\Accounting\MoneyTransaction;
use App\Widgets\Widget;
use Carbon\Carbon;
use Illuminate\Support\Facades\Gate;

class TransactionsWidget implements Widget
{
    public function authorize(): bool
    {
        return Gate::allows('view-accounting-summary');
    }

    public function view(): string
    {
        return 'accounting.dashboard.widgets.transactions';
    }

    public function args(): array
    {
        // TODO: Probably define on more general location
        setlocale(LC_TIME, \App::getLocale());

        $dateFrom = Carbon::now()->startOfMonth();
        $dateTo = (clone $dateFrom)->endOfMonth();

        return [
            'monthName' => $dateFrom->formatLocalized('%B %Y'),
            'spending' => MoneyTransaction::selectRaw('SUM(amount) as sum')
                ->whereDate('date', '>=', $dateFrom)
                ->whereDate('date', '<=', $dateTo)
                ->where('type', 'spending')
                ->get()
                ->first()
                ->sum,
            'wallet' => MoneyTransaction::currentWallet(),
        ];
    }
}
