<?php

namespace Modules\People\Imports;

use Modules\People\Entities\Person;

use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Maatwebsite\Excel\Imports\HeadingRowFormatter;

use PhpOffice\PhpSpreadsheet\Shared\Date;

class PeopleImport implements ToModel, WithHeadingRow, WithValidation, SkipsOnFailure
{
    use Importable, SkipsFailures;

    private $count = 0;

    public function __construct()
    {
        HeadingRowFormatter::default('none');
    }

    public function count(): int
    {
        return $this->count;
    }

    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        ++$this->count;

        return new Person([
            'family_name' => $row['Family Name'],
            'name' => $row['Name'],
            'date_of_birth' => isset($row['Date of birth']) ? self::parseDate($row['Date of birth']) : null,
            'nationality' => $row['Nationality'],
            'police_no' => $row['Police Number'],
            'registration_no' => $row['Registration Number'] ?? null,
            'section_card_no' => $row['Section Card Number'] ?? null,
            'languages' => !empty($row['Languages']) ? self::parseToArray($row['Languages']) : null,
            'remarks' => $row['Remarks'],
        ]);
    }

    private static function parseDate($value)
    {
        return Date::excelToDateTimeObject($value)->format(DATE_ISO8601);
    }

    private static function parseToArray($value)
    {
        return preg_split('/(\s*[,\/|]\s*)|(\s+and\s+)/', $value);
    }

    public function rules(): array
    {
        return [
            'Name' => [
                 'required',
            ],
            'Family Name' => [
                'required',
            ],
            'Date of birth' => [
                'nullable',
            ],
            'Nationality' => [
                'nullable',
            ],
            'Police Number' => [
                'nullable',
                'numeric'
            ],
            'Registration Number' => [
                'nullable',
            ],
            'Section Card Number' => [
                'nullable',
            ],
            'Languages' => [
                'nullable',
            ],
            'Remarks' => [
                'nullable',
            ],
        ];
    }

}
