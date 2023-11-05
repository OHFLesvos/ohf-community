<?php

namespace App\Exports\Visitors\Sheets;

use App\Exports\BaseExport;
use App\Exports\PageOrientation;
use App\Models\Visitors\VisitorCheckin;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class VisitorCheckInsExport extends BaseExport implements FromQuery, WithHeadings, WithMapping
{
    public function __construct(private readonly Carbon $dateFrom, private readonly Carbon $dateTo)
    {
        $this->orientation = PageOrientation::Portrait;
    }

    public function query(): \Illuminate\Database\Eloquent\Builder
    {
        return VisitorCheckin::query()
            ->whereDate('created_at', '>=', $this->dateFrom)
            ->whereDate('created_at', '<=', $this->dateTo)
            ->orderBy('created_at', 'desc')
            ->with('visitor');
    }

    public function title(): string
    {
        return __('Check-ins');
    }

    public function headings(): array
    {
        return [
            __('Date'),
            __('Anonymized'),
            __('Name'),
            __('ID Document Number'),
            __('Membership Number'),
            __('Gender'),
            __('Nationality'),
            __('Date of birth'),
            __('Living situation'),
            __('Liability form signed'),
            __('Purpose of visit'),
        ];
    }

    /**
     * @param  VisitorCheckin  $checkin
     */
    public function map($checkin): array
    {
        return [
            $checkin->created_at->format('Y-m-d'),
            $checkin->visitor->anonymized ? __('Yes') : __('No'),
            $checkin->visitor->name,
            $checkin->visitor->id_number,
            $checkin->visitor->membership_number,
            gender_label($checkin->visitor->gender),
            $checkin->visitor->nationality,
            optional($checkin->visitor->date_of_birth)->toDateString(),
            $checkin->visitor->living_situation,
            optional($checkin->visitor->liability_form_signed)->toDateString(),
            $checkin->purpose_of_visit,
        ];
    }
}
