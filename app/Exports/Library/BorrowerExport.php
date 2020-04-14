<?php

namespace App\Exports\Library;

use App\Exports\BaseExport;
use App\Models\People\Person;
use Illuminate\Database\Eloquent\Builder;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class BorrowerExport extends BaseExport implements FromQuery, WithHeadings, WithMapping
{
    public bool $activeOnly = false;
    public bool $overdueOnly = false;

    public function __construct()
    {
        $this->orientation = 'landscape';
    }

    public function query()
    {
        $query = Person::query();
        if ($this->overdueOnly) {
            $query->whereHas('bookLendings', function (Builder $query) {
                $query->overdue();
            });
        } else if ($this->activeOnly) {
            $query->whereHas('bookLendings', function (Builder $query) {
                $query->active();
            });
        } else {
            $query->has('bookLendings');
        }
        return $query->orderBy('name', 'asc');
    }

    public function title(): string
    {
        return __('library.borrowers');
    }

    public function headings(): array
    {
        $headings = [
            __('app.name'),
            __('people.family_name'),
            __('people.date_of_birth'),
            __('people.age'),
            __('people.gender'),
            __('people.nationality'),
            __('people.police_no'),
            '# ' . __('library.books'),
        ];
        if ($this->overdueOnly) {
            $headings[] = __('library.books');
            $headings[] = __('library.lent_until');
        } else if ($this->activeOnly) {
            $headings[] = __('library.books');
            $headings[] = __('library.lent_until');
            $headings[] = __('library.overdue');
        }
        return $headings;
    }

    /**
     * @param Person $person
     */
    public function map($person): array
    {
        $mapping = [
            $person->name,
            $person->family_name,
            $person->date_of_birth,
            $person->age,
            $person->gender,
            $person->nationality,
            $person->police_no,
        ];
        if ($this->overdueOnly) {
            $mapping[] = $person->bookLendings()->overdue()->count();
            $mapping[] = $person->bookLendings()->overdue()->get()->pluck('book.title')->join(', ');
            $mapping[] = $person->bookLendings()->overdue()->orderBy('return_date', 'asc')->first()->return_date->toDateString();

        } else if ($this->activeOnly) {
            $mapping[] = $person->bookLendings()->active()->count();
            $mapping[] = $person->bookLendings()->active()->get()->pluck('book.title')->join(', ');
            $mapping[] = $person->bookLendings()->active()->orderBy('return_date', 'asc')->first()->return_date->toDateString();
            $mapping[] = $person->bookLendings()->overdue()->exists() ? __('app.yes') : __('app.no');
        } else {
            $mapping[] = (int) $person->bookLendings()->count();
        }
        return $mapping;
    }

}
