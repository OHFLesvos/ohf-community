<?php

namespace App\Exports\Accounting;

use App\Exports\Accounting\Sheets\TransactionsMonthSheet;
use App\Exports\Accounting\Sheets\TransactionsSummarySheet;
use App\Exports\DefaultFormatting;
use App\Exports\PageOrientation;
use App\Models\Accounting\Transaction;
use App\Models\Accounting\Wallet;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Events\BeforeExport;
use Maatwebsite\Excel\Events\BeforeWriting;

class TransactionsMonthsExport implements WithEvents, WithMultipleSheets
{
    use DefaultFormatting, Exportable;

    /**
     * @param  Wallet|null  $wallet  Wallet
     * @param  string|null  $filter  Simple filter
     * @param  array<string>  $advancedFilter  Filter conditions
     */
    public function __construct(
        private ?Wallet $wallet,
        private ?string $filter = null,
        private array $advancedFilter = [])
    {
        setlocale(LC_TIME, \App::getLocale());
    }

    public function sheets(): array
    {
        $months = Transaction::query()
            ->forWallet($this->wallet)
            ->forFilter($this->filter)
            ->forAdvancedFilter($this->advancedFilter)
            ->selectRaw('MONTH(date) as month')
            ->selectRaw('YEAR(date) as year')
            ->groupBy(DB::raw('MONTH(date)'))
            ->groupBy(DB::raw('YEAR(date)'))
            ->orderBy('year', 'asc')
            ->orderBy('month', 'asc')
            ->get()
            ->map(fn ($e) => new Carbon($e['year'].'-'.$e['month'].'-01'))
            ->map(fn (Carbon $c) => $c->startOfMonth())
            ->toArray();

        $sheets = [];

        // Transactions by month
        foreach ($months as $month) {
            $sheet = new TransactionsMonthSheet($this->wallet, $month, $this->filter, $this->advancedFilter);
            $sheet->orientation = PageOrientation::Landscape;
            $sheets[] = $sheet;
        }

        // Summary
        $sheets[] = new TransactionsSummarySheet($months);

        return $sheets;
    }

    public function registerEvents(): array
    {
        return [
            BeforeExport::class => function (BeforeExport $event) {
                $spreadsheet = $event->writer->getDelegate();
                $this->setupSpreadsheet($spreadsheet);
            },
            BeforeWriting::class => function (BeforeWriting $event) {
                $spreadsheet = $event->writer->getDelegate();
                $spreadsheet->setActiveSheetIndex($spreadsheet->getSheetCount() - 1);
            },
        ];
    }
}
