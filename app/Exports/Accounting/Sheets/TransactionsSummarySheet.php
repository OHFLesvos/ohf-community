<?php

namespace App\Exports\Accounting\Sheets;

use App\Exports\BaseExport;
use Carbon\Carbon;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use PhpOffice\PhpSpreadsheet\Style\Color;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class TransactionsSummarySheet extends BaseExport implements FromView, WithColumnFormatting
{
    /**
     * Months
     *
     * @var array<Carbon>
     */
    private array $months;

    /**
     * Constructor.
     *
     * @param array<Carbon> $months
     */
    public function __construct(array $months)
    {
        $this->months = $months;
    }

    public function view(): View
    {
        return view('accounting.transactions.export_summary', [
            'months' => $this->months,
        ]);
    }

    public function title(): string
    {
        return __('Summary');
    }

    public function columnFormats(): array
    {
        return [
            'B' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1,
            'C' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1,
            'D' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1,
            'E' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1,
        ];
    }

    protected function applyStyles(Worksheet $sheet)
    {
        parent::applyStyles($sheet);
        $sheet->getStyle('B2:B'.$sheet->getHighestRow())->getFont()->setColor(new Color(Color::COLOR_DARKGREEN));
        $sheet->getStyle('C2:C'.$sheet->getHighestRow())->getFont()->setColor(new Color(Color::COLOR_DARKRED));
    }
}
