<?php

namespace Modules\School\Exports;

use App\Exports\BaseExport;

use Modules\School\Entities\Student;
use Modules\School\Entities\SchoolClass;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Auth;

use Carbon\Carbon;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

use PhpOffice\PhpSpreadsheet\Cell\Coordinate;

class StudentsExport extends BaseExport implements FromCollection, WithHeadings, WithMapping
{
    private $students;

    public function __construct($students)
    {
        $this->setOrientation('landscape');

        $this->students = $students;
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection(): Collection
    {
        return $this->students->orderBy('name')
            ->orderBy('family_name')
            ->get(); 
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
            __('app.id'),
            __('app.name'),
            __('people::people.family_name'),
            __('people::people.nationality'),
            __('people::people.date_of_birth'),
            __('people::people.age'),
            __('people::people.police_no'),
            __('app.remarks'),
        ];
    }

    /**
    * @var Donor $donor
    */
    public function map($student): array
    {
        return [
            $student->getRouteKey(),
            $student->name,
            $student->family_name,
            $student->nationality,
            $student->date_of_birth,
            $student->age,
            $student->police_no_formatted,
            $student->participation->remarks,
        ];
    }

}
