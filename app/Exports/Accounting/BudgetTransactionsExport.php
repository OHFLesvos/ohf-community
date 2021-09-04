<?php

namespace App\Exports\Accounting;

use App\Exports\BaseExport;
use App\Models\Accounting\Budget;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use PhpOffice\PhpSpreadsheet\Style\Color;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class BudgetTransactionsExport extends BaseExport implements FromQuery, WithHeadings, WithMapping, WithColumnFormatting
{
    private Budget $budget;

    public function __construct(
        Budget $budget
    ) {
        $this->budget = $budget;
        $this->orientation = 'landscape';
    }

    public function query(): \Illuminate\Database\Eloquent\Builder
    {
        return $this->budget->transactions()
            ->getQuery()
            ->orderBy('date', 'ASC')
            ->orderBy('created_at', 'ASC');
    }

    public function title(): string
    {
        return __('Transactions');
    }

    public function headings(): array
    {
        return [
            __('Date'),
            __('Receipt No.'),
            __('Income'),
            __('Spending'),
            __('Category'),
            __('Description'),
            __('Supplier'),
            __('Registered'),
        ];
    }

    /**
     * @param Transaction $transaction
     */
    public function map($transaction): array
    {
        return [
            $transaction->date,
            $transaction->receipt_no,
            $transaction->type == 'income' ? $transaction->amount : '',
            $transaction->type == 'spending' ? $transaction->amount : '',
            $transaction->category->name,
            $transaction->description,
            optional($transaction->supplier)->name,
            $transaction->created_at,
        ];
    }

    public function columnFormats(): array
    {
        return [
            'C' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1,
            'D' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1,
        ];
    }

    protected function applyStyles(Worksheet $sheet)
    {
        parent::applyStyles($sheet);
        $sheet->getStyle('C2:C' . $sheet->getHighestRow())->getFont()->setColor(new Color(Color::COLOR_DARKGREEN));
        $sheet->getStyle('D2:D' . $sheet->getHighestRow())->getFont()->setColor(new Color(Color::COLOR_DARKRED));
    }
}
