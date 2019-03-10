<?php

namespace Modules\Accounting\Exports;

use App\Exports\BaseExport;
use Modules\Accounting\Entities\MoneyTransaction;

use Illuminate\Support\Collection;

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
            __('accounting::accounting.income'),
            __('accounting::accounting.spending'),
            __('accounting::accounting.receipt').' #',
            __('accounting::accounting.beneficiary'),
            __('app.project'),
            __('app.description'),
            __('app.registered'),
            __('app.author'),
            __('accounting::accounting.wallet_owner'),
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
            $transaction->project,
            $transaction->description,
            $transaction->created_at,
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
