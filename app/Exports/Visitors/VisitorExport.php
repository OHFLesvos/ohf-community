<?php

namespace App\Exports\Visitors;

use App\Exports\BaseExport;
use App\Models\Visitors\VisitorCheckin;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class VisitorExport extends BaseExport implements FromQuery, WithHeadings, WithMapping
{
    public function __construct()
    {
        $this->orientation = 'portrait';
    }

    public function query(): \Illuminate\Database\Eloquent\Builder
    {
        return VisitorCheckin::orderBy('created_at', 'desc')
            ->with('visitor');
    }

    public function title(): string
    {
        return __('Visitors check-ins');
    }

    public function headings(): array
    {
        return [
            __('Date'),
            __('Anonymized'),
            __('Name'),
            __('ID Number'),
            __('Gender'),
            __('Nationality'),
            __('Date of birth'),
            __('Living situation'),
            __('Purpose of visit'),
        ];
    }

    /**
     * @param VisitorCheckin $checkin
     */
    public function map($checkin): array
    {
        return [
            $checkin->created_at->format('Y-m-d'),
            $checkin->visitor->anonymized ? __('Yes') : __('No'),
            $checkin->visitor->name,
            $checkin->visitor->id_number,
            $checkin->visitor->genderLabel,
            $checkin->visitor->nationality,
            $checkin->visitor->date_of_birth,
            $checkin->visitor->living_situation,
            $checkin->purpose_of_visit,
        ];
    }
}
