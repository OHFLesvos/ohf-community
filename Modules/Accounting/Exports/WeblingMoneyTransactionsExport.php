<?php

namespace Modules\Accounting\Exports;

use App\Exports\BaseExport;

use Modules\Accounting\Entities\MoneyTransaction;
use Modules\Accounting\Http\Controllers\MoneyTransactionsController;

use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;

use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

use Carbon\Carbon;

class WeblingMoneyTransactionsExport extends BaseExport implements FromQuery, WithHeadings, WithMapping, WithColumnFormatting
{
    private $filter;

    public function __construct($filter = [])
    {
        $this->filter = $filter;
        $this->setOrientation('landscape');
    }

    public function query(): \Illuminate\Database\Eloquent\Builder
    {
        $qry = MoneyTransaction
                ::orderBy('date', 'ASC')
                ->orderBy('created_at', 'ASC');
        MoneyTransactionsController::applyFilterToQuery($this->filter, $qry);
        return $qry;
    }

    /**
     * @return string
     */
    public function title(): string
    {
        return __('accounting::accounting.transactions');
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        return [
            'Datum',
            'Gutschrift',
            'Lastschrift',
            'Buchungstext',
        ];
    }

    /**
    * @var MoneyTransaction $transaction
    */
    public function map($transaction): array
    {
        return [
            (new Carbon($transaction->date))->format('d.m.Y'),
            $transaction->type == 'income' ? $transaction->amount : '',
            $transaction->type == 'spending' ? $transaction->amount : '',
            ($transaction->receipt_no > 0 ? ('#'. $transaction->receipt_no . ' ') : '') . '['. $transaction->category . ']  ' . ($transaction->project != null ? ('(' . $transaction->project . ') ') : '') . $transaction->description,
        ];
    }

    /**
     * @return array
     */
    public function columnFormats(): array
    {
        return [
            // 'A' => NumberFormat::FORMAT_DATE_DDMMYYYY,
            'B' => NumberFormat::FORMAT_NUMBER_00,
            'C' => NumberFormat::FORMAT_NUMBER_00,
        ];
    }
}
