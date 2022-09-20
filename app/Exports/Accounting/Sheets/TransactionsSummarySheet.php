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
     * @param  array<Carbon>  $months Months
     */
    public function __construct(private array $months)
    {
    }

    public function view(): View
    {
        return view('accounting.export_summary', [
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
            'C' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1,
            'D' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1,
            'E' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1,
            'F' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1,
        ];
    }

    protected function applyStyles(Worksheet $sheet)
    {
        parent::applyStyles($sheet);
        $sheet->getStyle('C2:C'.$sheet->getHighestRow())->getFont()->setColor(new Color(Color::COLOR_DARKGREEN));
        $sheet->getStyle('D2:D'.$sheet->getHighestRow())->getFont()->setColor(new Color(Color::COLOR_DARKRED));
    }
}
