<?php

namespace App\Exports\Visitors\Sheets;

use App\Exports\BaseExport;
use App\Exports\PageOrientation;
use App\Models\Visitors\Visitor;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class VisitorDataExport extends BaseExport implements FromQuery, WithHeadings, WithMapping
{
    public function __construct()
    {
        $this->orientation = PageOrientation::Portrait;
    }

    public function query(): \Illuminate\Database\Eloquent\Builder
    {
        return Visitor::orderBy('anonymized')
            ->orderBy('name', 'asc')
            ->with('checkins');
    }

    public function title(): string
    {
        return __('Visitors');
    }

    public function headings(): array
    {
        return [
            __('Anonymized'),
            __('Name'),
            __('ID Number'),
            __('Gender'),
            __('Nationality'),
            __('Date of birth'),
            __('Living situation'),
            __('Liability form signed'),
            __('Remarks'),
            __('Registered'),
            __('Check-ins'),
        ];
    }

    /**
     * @param  Visitor  $visitor
     */
    public function map($visitor): array
    {
        return [
            $visitor->anonymized ? __('Yes') : __('No'),
            $visitor->name,
            $visitor->id_number,
            gender_label($visitor->gender),
            $visitor->nationality,
            optional($visitor->date_of_birth)->toDateString(),
            $visitor->living_situation,
            optional($visitor->liability_form_signed)->toDateString(),
            $visitor->remarks,
            $visitor->created_at->format('Y-m-d'),
            $visitor->checkins->count(),
        ];
    }
}
