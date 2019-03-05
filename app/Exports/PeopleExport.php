<?php

namespace App\Exports;

use App\Person;
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
        return __('people.people');
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        return [
            __('people.family_name'),
            __('people.name'),
            __('people.date_of_birth'),
            __('people.age'),
            __('people.nationality'),
            __('people.police_number'),
            __('people.registration_number'),
            __('people.section_card_number'),
            __('people.languages'),
            __('app.registered'),
            __('people.remarks'),
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
            $person->created_at->toDateString(),
            $person->remarks,
        ];
    }
}
