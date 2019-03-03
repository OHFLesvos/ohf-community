<?php

namespace App\Exports;

use App\MoneyTransaction;
use App\Exports\Sheets\MoneyTransactionsMonthSheet;
use App\Exports\Sheets\MoneyTransactionsSummarySheet;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Carbon\Carbon;

class MoneyTransactionsExport implements WithMultipleSheets
{
    use Exportable;

    public function __construct()
    {

    }

    /**
     * @return array
     */
    public function sheets(): array
    {
        // TODO: Probably define on more general location
        setlocale(LC_TIME, \App::getLocale());

        $months = MoneyTransaction
            ::select(DB::raw('MONTH(date) as month'), DB::raw('YEAR(date) as year'))
            ->groupBy(DB::raw('MONTH(date)'))
            ->groupBy(DB::raw('YEAR(date)'))
            ->orderBy('year', 'asc')
            ->orderBy('month', 'asc')
            ->get()
            ->map(function($e){
                return (new Carbon($e->year.'-'.$e->month.'-01'))->startOfMonth();
            })
            ->toArray();

        $sheets = [];

        // Transactions by month
        foreach ($months as $month) {
            $sheet = new MoneyTransactionsMonthSheet($month);
            $sheet->setOrientation('landscape');
            $sheets[] = $sheet;
        }

        // Summary
        $sheets[] = new MoneyTransactionsSummarySheet($months);

        return $sheets;
    }
}
