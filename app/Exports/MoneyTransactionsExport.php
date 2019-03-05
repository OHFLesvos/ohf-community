<?php

namespace App\Exports;

use App\MoneyTransaction;
use App\Exports\Sheets\MoneyTransactionsMonthSheet;
use App\Exports\Sheets\MoneyTransactionsSummarySheet;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\BeforeExport;
use Maatwebsite\Excel\Events\BeforeWriting;
use PhpOffice\PhpSpreadsheet\Spreadsheet;

class MoneyTransactionsExport implements WithMultipleSheets, WithEvents
{
    use Exportable;

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
                $this->finishSpreadsheet($spreadsheet);
            },
        ];
    }

    protected function setupSpreadsheet(Spreadsheet $spreadsheet) {
        // Creator
        $spreadsheet->getProperties()->setCreator(env('APP_NAME'));
    
        // Default font
        $spreadsheet->getDefaultStyle()->getFont()->setSize(9);
    }
    
    protected function finishSpreadsheet(Spreadsheet $spreadsheet) {
        $spreadsheet->setActiveSheetIndex($spreadsheet->getSheetCount() - 1);
    }
}
