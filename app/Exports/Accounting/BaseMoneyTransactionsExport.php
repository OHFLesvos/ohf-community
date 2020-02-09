<?php

namespace App\Exports\Accounting;

use App\Exports\BaseExport;

use App\Models\Accounting\MoneyTransaction;

use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;

use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Color;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

abstract class BaseMoneyTransactionsExport extends BaseExport implements FromQuery, WithHeadings, WithMapping, WithColumnFormatting
{
    /**
     * @return array
     */
    public function headings(): array
    {
        return [
            __('app.date'),
            __('accounting.income'),
            __('accounting.spending'),
            __('accounting.receipt').' #',
            __('accounting.beneficiary'),
            __('app.category'),
            __('app.project'),
            __('app.description'),
            __('app.registered'),
            __('accounting.booked'),
            __('app.author'),
            __('accounting.wallet_owner'),
            __('app.remarks'),
        ];
    }

    /**
    * @var MoneyTransaction $transaction
    */
    public function map($transaction): array
    {
        $audit = $transaction->audits()->first();
        return [
            $transaction->date,
            $transaction->type == 'income' ? $transaction->amount : '',
            $transaction->type == 'spending' ? $transaction->amount : '',
            $transaction->receipt_no,
            $transaction->beneficiary,
            $transaction->category,
            $transaction->project,
            $transaction->description,
            $transaction->created_at,
            $transaction->booked ? __('app.yes') : __('app.no'),
            isset($audit) ? $audit->getMetadata()['user_name'] : '',
            $transaction->wallet_owner,
            $transaction->remarks,
        ];
    }

    /**
     * @return array
     */
    public function columnFormats(): array
    {
        return [
            'B' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1,
            'C' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1,
        ];
    }

    protected function applyStyles(Worksheet $sheet)
    {
        parent::applyStyles($sheet);
        $sheet->getStyle('B2:B'.$sheet->getHighestRow())->getFont()->setColor(new Color(Color::COLOR_DARKGREEN));
        $sheet->getStyle('C2:C'.$sheet->getHighestRow())->getFont()->setColor(new Color(Color::COLOR_DARKRED));
    }
}
