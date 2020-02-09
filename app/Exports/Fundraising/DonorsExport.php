<?php

namespace App\Exports\Fundraising;

use App\Exports\BaseExport;

use App\Models\Fundraising\Donor;
use App\Models\Fundraising\Donation;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Auth;

use Carbon\Carbon;

use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;

use PhpOffice\PhpSpreadsheet\Cell\Coordinate;

class DonorsExport extends BaseExport implements FromQuery, WithHeadings, WithMapping, WithColumnFormatting
{
    public function __construct()
    {
        $this->setOrientation('landscape');
    }

    public function query(): \Illuminate\Database\Eloquent\Builder
    {
        return Donor
            ::orderBy('first_name')
            ->orderBy('last_name')
            ->orderBy('company');
    }

    /**
     * @return string
     */
    public function title(): string
    {
        return __('fundraising.donors');
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        $headings = [
            __('app.salutation'),
            __('app.first_name'),
            __('app.last_name'),
            __('app.company'),
            __('app.street'),
            __('app.zip'),
            __('app.city'),
            __('app.country'),
            __('app.email'),
            __('app.phone'),
            __('app.correspondence_language'),
            __('app.registered'),
            __('app.tags'),
            __('app.remarks'),
        ];
        if (Auth::user()->can('list', Donation::class)) {
            $headings[] = __('fundraising.donations') . ' ' . Carbon::now()->subYear()->year;
            $headings[] = __('fundraising.donations') . ' ' . Carbon::now()->year;
            foreach (Config::get('fundraising.currencies') as $currency) {
                $headings[] = $currency . ' in ' . Carbon::now()->year;
            }
        }
        return $headings;
    }

    /**
    * @var Donor $donor
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
            $donor->remarks,
        ];
        if (Auth::user()->can('list', Donation::class)) {
            $map[] = $donor->amountPerYear(Carbon::now()->subYear()->year) ?? 0;
            $map[] = $donor->amountPerYear(Carbon::now()->year) ?? 0;

            $apybc = $donor->amountPerYearByCurrencies(Carbon::now()->year) ?? [];
            foreach (Config::get('fundraising.currencies') as $currency) {
                $map[] = $apybc[$currency] ?? 0;
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
        if (Auth::user()->can('list', Donation::class)) {
            $formats['O'] = Config::get('fundraising.base_currency_excel_format');
            $formats['P'] = Config::get('fundraising.base_currency_excel_format');
            $i = Coordinate::columnIndexFromString('P');
            foreach (Config::get('fundraising.currencies') as $currency) {
                $column = Coordinate::stringFromColumnIndex(++$i);
                $formats[$column] =  Config::get('fundraising.currencies_excel_format')[$currency];
            }
        }
        return $formats;
    }
}