<?php

namespace App\Exports\Fundraising\Sheets;

use App\Exports\BaseExport;

use App\Models\Fundraising\Donation;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Config;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Font;

class DonationsSheet extends BaseExport implements FromCollection, WithHeadings, WithMapping
{
    private $donations;
    private $year;

    protected $currencyColumn = 'G';
    protected $exchangedCurrencyColumn = 'H';

    public function __construct(Collection $donations, ?int $year = null)
    {
        $this->donations = $donations;
        $this->year = $year;

        $this->setOrientation('landscape');
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection(): Collection
    {
        return $this->donations;
    }

    /**
     * @return string
     */
    public function title(): string
    {
        return __('fundraising.donations') . ($this->year != null ? ' ' . $this->year : '');
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        return [
            __('app.date'),
            __('fundraising.channel'),
            __('fundraising.purpose'),
            __('fundraising.reference'),
            __('fundraising.in_name_of'),
            __('fundraising.thanked'),
            __('app.amount'),
            __('fundraising.exchange_amount'),
        ];
    }

    /**
    * @var Donation $donation
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
            $sheet->getStyle($this->currencyColumn . ($i + 2))->getNumberFormat()->setFormatCode(Config::get('fundraising.currencies_excel_format')[$this->donations[$i]->currency]);
        }

        if ($cnt > 0) {
            $sumCell = $this->exchangedCurrencyColumn . ($cnt + 2);
            
            // Set currency format
            $sheet->getStyle($this->exchangedCurrencyColumn . '1:' . $sumCell)->getNumberFormat()->setFormatCode(Config::get('fundraising.base_currency_excel_format'));

            // Total sum cell value
            $sumCell = $this->exchangedCurrencyColumn . ($cnt + 2);
            // $sheet->setCellValue($sumCell, '=SUM(H2:H' . ($cnt + 1) . ')');
            $sheet->setCellValue($sumCell, $this->donations->sum('exchange_amount'));
            $sheet->getStyle($sumCell)->getFont()->setUnderline(Font::UNDERLINE_DOUBLEACCOUNTING);
        }
    }

}
