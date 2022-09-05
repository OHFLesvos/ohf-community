<?php

namespace App\Exports\Fundraising\Sheets;

use App\Exports\BaseExport;
use App\Models\Fundraising\Donation;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use PhpOffice\PhpSpreadsheet\Style\Font;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class DonationsSheet extends BaseExport implements FromCollection, WithHeadings, WithMapping
{
    private Collection $donations;

    private ?int $year;

    protected string $currencyColumn = 'G';

    protected string $exchangedCurrencyColumn = 'H';

    public function __construct(Collection $donations, ?int $year = null)
    {
        $this->donations = $donations;
        $this->year = $year;

        $this->orientation = 'landscape';
    }

    public function collection(): Collection
    {
        return $this->donations;
    }

    public function title(): string
    {
        return __('Donations').($this->year !== null ? ' '.$this->year : '');
    }

    public function headings(): array
    {
        return [
            __('Date'),
            __('Channel'),
            __('Purpose'),
            __('Reference'),
            __('In the name of'),
            __('Thanked'),
            __('Amount'),
            __('Exchange amount'),
        ];
    }

    /**
     * @param  Donation  $donation
     */
    public function map($donation): array
    {
        return [
            $donation->date,
            $donation->channel,
            $donation->purpose,
            $donation->reference,
            $donation->in_name_of,
            optional($donation->thanked)->toDateString(),
            $donation->amount,
            $donation->exchange_amount,
        ];
    }

    protected function applyStyles(Worksheet $sheet)
    {
        parent::applyStyles($sheet);

        $cnt = $this->donations->count();

        // Set exchange currency format
        for ($i = 0; $i < $cnt; $i++) {
            $sheet->getStyle($this->currencyColumn.($i + 2))->getNumberFormat()->setFormatCode(config('fundraising.currencies_excel_format')[$this->donations[$i]->currency]);
        }

        if ($cnt > 0) {
            $sumCell = $this->exchangedCurrencyColumn.($cnt + 2);

            // Set currency format
            $sheet->getStyle($this->exchangedCurrencyColumn.'1:'.$sumCell)->getNumberFormat()->setFormatCode(config('fundraising.base_currency_excel_format'));

            // Total sum cell value
            $sumCell = $this->exchangedCurrencyColumn.($cnt + 2);
            // $sheet->setCellValue($sumCell, '=SUM(H2:H' . ($cnt + 1) . ')');
            $sheet->setCellValue($sumCell, $this->donations->sum('exchange_amount'));
            $sheet->getStyle($sumCell)->getFont()->setUnderline(Font::UNDERLINE_DOUBLEACCOUNTING);
        }
    }
}
