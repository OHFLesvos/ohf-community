<?php

namespace App\Exports\Accounting;

use App\Exports\DefaultFormatting;

use App\Models\Accounting\MoneyTransaction;
use App\Exports\Accounting\Sheets\MoneyTransactionsMonthSheet;
use App\Exports\Accounting\Sheets\MoneyTransactionsSummarySheet;
use App\Http\Controllers\Accounting\MoneyTransactionsController;

use Illuminate\Support\Facades\DB;

use Carbon\Carbon;

use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\BeforeExport;
use Maatwebsite\Excel\Events\BeforeWriting;

class MoneyTransactionsMonthsExport implements WithMultipleSheets, WithEvents
{
    use Exportable, DefaultFormatting;

    private $filter;

    public function __construct($filter = [])
    {
        $this->filter = $filter;

        Carbon::setUtf8(true);
        // TODO: Probably define on more general location
        setlocale(LC_TIME, \App::getLocale());
    }

    /**
     * @return array
     */
    public function sheets(): array
    {
        $qry = MoneyTransaction
            ::select(DB::raw('MONTH(date) as month'), DB::raw('YEAR(date) as year'))
            ->groupBy(DB::raw('MONTH(date)'))
            ->groupBy(DB::raw('YEAR(date)'))
            ->orderBy('year', 'asc')
            ->orderBy('month', 'asc');
        MoneyTransactionsController::applyFilterToQuery($this->filter, $qry, true);
        $months = $qry
            ->get()
            ->map(function($e){
                return (new Carbon($e->year.'-'.$e->month.'-01'))->startOfMonth();
            })
            ->toArray();

        $sheets = [];

        // Transactions by month
        foreach ($months as $month) {
            $sheet = new MoneyTransactionsMonthSheet($month, $this->filter);
            $sheet->setOrientation('landscape');
            $sheets[] = $sheet;
        }

        // Summary
        $sheets[] = new MoneyTransactionsSummarySheet($months);

        return $sheets;
    }

    /**
     * @return array
     */
    public function registerEvents(): array
    {
        return [
            BeforeExport::class => function(BeforeExport $event) {
                $spreadsheet = $event->writer->getDelegate();
                $this->setupSpreadsheet($spreadsheet);
            },
            BeforeWriting::class => function(BeforeWriting $event) {
                $spreadsheet = $event->writer->getDelegate();
                $spreadsheet->setActiveSheetIndex($spreadsheet->getSheetCount() - 1);
            },
        ];
    }

}
