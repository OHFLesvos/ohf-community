<?php

namespace Modules\People\Exports;

use App\Exports\BaseExport;

use Modules\People\Entities\Person;

use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class PeopleExport extends BaseExport implements FromQuery, WithHeadings, WithMapping
{
    public function __construct()
    {
        $this->setOrientation('landscape');
    }

    public function query(): \Illuminate\Database\Eloquent\Builder
    {
        return Person::orderBy('name', 'asc')
            ->orderBy('family_name', 'asc')
            ->orderBy('name', 'asc');
    }

    /**
     * @return string
     */
    public function title(): string
    {
        return __('people::people.people');
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        return [
            'Family Name',
            'Name',
            'Date of birth',
            'Age',
            'Nationality',
            'Police Number',
            'Registration Number',
            'Section Card Number',
            'Languages',
            'Registered',
            'Remarks',
        ];
    }

    /**
    * @var Person $person
    */
    public function map($person): array
    {
        return [
            $person->family_name,
            $person->name,
            $person->date_of_birth,
            $person->age,
            $person->nationality,
            $person->police_no,
            $person->registration_no,
            $person->section_card_no,
            is_array($person->languages) ? implode(', ', $person->languages) : $person->languages,
            optional($person->created_at)->toDateString(),
            $person->remarks,
        ];
    }
}
