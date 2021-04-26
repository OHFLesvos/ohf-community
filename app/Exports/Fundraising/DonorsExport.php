<?php

namespace App\Exports\Fundraising;

use App\Exports\BaseExport;
use App\Models\Fundraising\Donation;
use App\Models\Fundraising\Donor;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class DonorsExport extends BaseExport implements FromQuery, WithHeadings, WithMapping, WithColumnFormatting
{
    private Collection $usedCurrenciesChannels;
    private array $years;

    public function __construct()
    {
        $this->orientation = 'landscape';

        $this->years = [
            now()->subYear()->year,
            now()->year,
        ];

        $this->usedCurrenciesChannels = Donation::select('currency', 'channel')
            ->selectRaw('YEAR(date) as year')
            ->selectRaw('SUM(amount) as amount')
            ->having('amount', '>', 0)
            ->where(function ($qry) {
                foreach ($this->years as $year) {
                    $qry->orWhereYear('date', $year);
                }
            })
            ->groupBy('currency')
            ->groupBy('channel')
            ->groupBy('year')
            ->orderBy('year')
            ->orderBy('currency')
            ->orderBy('channel')
            ->get();
    }

    public function query(): \Illuminate\Database\Eloquent\Builder
    {
        return Donor::orderBy('first_name')
            ->orderBy('last_name')
            ->orderBy('company');
    }

    public function title(): string
    {
        return __('Donors');
    }

    public function headings(): array
    {
        $headings = [
            __('Salutation'),
            __('First Name'),
            __('Last Name'),
            __('Company'),
            __('Street'),
            __('ZIP'),
            __('City'),
            __('Country'),
            __('E-Mail Address'),
            __('Phone'),
            __('Correspondence language'),
            __('Registered'),
            __('Tags'),
            __('Comments'),
        ];
        if (Auth::user()->can('viewAny', Donation::class)) {
            foreach ($this->years as $year) {
                $headings[] = __('Donations') . ' ' . $year;
            }
            foreach ($this->usedCurrenciesChannels as $cc) {
                $headings[] = $cc->currency . ' via ' . $cc->channel . ' in ' . $cc->year;
            }
        }
        return $headings;
    }

    /**
     * @param Donor $donor
     */
    public function map($donor): array
    {
        $map = [
            $donor->salutation,
            $donor->first_name,
            $donor->last_name,
            $donor->company,
            $donor->street,
            $donor->zip,
            $donor->city,
            $donor->country_name,
            $donor->email,
            $donor->phone,
            $donor->language,
            $donor->created_at,
            $donor->tags->sortBy('name')->pluck('name')->implode(', '),
            $donor->comments->sortBy('created_at')->pluck('content')->implode('; '),
        ];
        if (Auth::user()->can('viewAny', Donation::class)) {
            foreach ($this->years as $year) {
                $map[] = $donor->amountPerYear($year) ?? 0;
            }
            $amounts = $donor->amountByChannelCurrencyYear();
            foreach ($this->usedCurrenciesChannels as $cc) {
                $map[] = optional($amounts->where('year', $cc->year)
                    ->where('currency', $cc->currency)
                    ->where('channel', $cc->channel)
                    ->first())->total ?? null;
            }
        }
        return $map;
    }

    /**
     * @return array
     */
    public function columnFormats(): array
    {
        $formats = [];
        if (Auth::user()->can('viewAny', Donation::class)) {
            foreach ($this->years as $year) {
                $formats['O'] = config('fundraising.base_currency_excel_format');
                $formats['P'] = config('fundraising.base_currency_excel_format');
            }
            $i = Coordinate::columnIndexFromString('P');
            foreach ($this->usedCurrenciesChannels as $cc) {
                $i++;
                $column = Coordinate::stringFromColumnIndex($i);
                $formats[$column] = config('fundraising.currencies_excel_format')[$cc->currency];
            }
        }
        return $formats;
    }

    protected function setupView(Worksheet $sheet)
    {
        parent::setupView($sheet);

        $sheet->freezePane('A2');
    }
}
