<?php

namespace App\Exports\Visitors;

use App\Exports\BaseExport;
use App\Models\Visitors\Visitor;
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
        $retention_date = today()->subDays(config('visitors.retention_days'))->toDateString();
        return Visitor::query()
            ->whereDate('entered_at', '>=', $retention_date)
            ->orderBy('entered_at', 'desc')
            ->orderBy('last_name', 'asc')
            ->orderBy('first_name', 'asc');
    }

    public function title(): string
    {
        return __('Visitors');
    }

    public function headings(): array
    {
        return [
            __('Date'),
            __('Check-in'),
            __('Checkout'),
            __('Last Name'),
            __('First Name'),
            __('Type'),
            __('ID Number'),
            __('Place of residence'),
            __('Activity / Program'),
            __('Organization'),
        ];
    }

    /**
     * @param Visitor $visitor
     */
    public function map($visitor): array
    {
        $types = [
            'visitor' => __('Visitor'),
            'participant' => __('Participant'),
            'staff' => __('Volunteer / Staff'),
            'external' => __('External visitor'),
        ];
        return [
            $visitor->entered_at->format('Y-m-d'),
            $visitor->entered_at->format('H:i'),
            optional($visitor->left_at)->format('H:i'),
            $visitor->last_name,
            $visitor->first_name,
            isset($types[$visitor->type]) ? $types[$visitor->type] : $visitor->type,
            $visitor->id_number,
            $visitor->place_of_residence,
            $visitor->activity,
            $visitor->organization,
        ];
    }
}
