<?php

namespace Modules\School\Exports;

use App\Exports\BaseExport;

use Modules\School\Entities\Student;
use Modules\School\Entities\SchoolClass;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Auth;

use Carbon\Carbon;

use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

use PhpOffice\PhpSpreadsheet\Cell\Coordinate;

class StudentsExport extends BaseExport implements FromQuery, WithHeadings, WithMapping
{
    private $students;

    public function __construct($students)
    {
        $this->setOrientation('landscape');

        $this->students = $students;
    }

    public function query(): \Illuminate\Database\Eloquent\Builder
    {
        return $this->students->orderBy('name')
            ->orderBy('family_name')
            ->getQuery();
    }

    /**
     * @return string
     */
    public function title(): string
    {
        return __('school::students.students');
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        return [
            __('app.first_name'),
            __('app.last_name'),
            __('people::people.nationality'),
            __('people::people.date_of_birth'),
            __('people::people.age')
        ];
    }

    /**
    * @var Donor $donor
    */
    public function map($student): array
    {
        return [
            $student->name,
            $student->family_name,
            $student->nationality,
            $student->date_of_birth,
            $student->age,
        ];
    }

}
