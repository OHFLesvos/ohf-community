<?php

namespace App\Exports\Bank;

use App\Exports\BaseExport;
use App\Models\Bank\CouponType;
use App\Models\People\Person;
use Illuminate\Database\Eloquent\Collection;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class BankExport extends BaseExport implements FromQuery, WithHeadings, WithMapping
{
    private Collection $couponTypes;

    public function __construct()
    {
        $this->orientation = 'landscape';

        $this->couponTypes = CouponType::orderBy('order')
            ->orderBy('name')
            ->get();
    }

    public function query()
    {
        return Person::orderBy('name', 'asc')
            ->orderBy('family_name', 'asc')
            ->orderBy('name', 'asc');
    }

    public function title(): string
    {
        return __('people.withdrawals');
    }

    public function headings(): array
    {
        $headings = [
            __('people.id'),
            __('people.family_name'),
            __('people.name'),
            __('people.date_of_birth'),
            __('people.age'),
            __('people.nationality'),
            __('people.police_number'),
        ];
        foreach ($this->couponTypes as $coupon) {
            $headings[] = $coupon->name;
        }
        $headings[] = __('people.remarks');
        return $headings;
    }

    /**
     * @param Person $person
     */
    public function map($person): array
    {
        $mapping = [
            $person->public_id,
            $person->family_name,
            $person->name,
            $person->date_of_birth,
            $person->age,
            $person->nationality,
            $person->police_no,
        ];
        foreach ($this->couponTypes as $coupon) {
            if ($person->eligibleForCoupon($coupon)) {
                $lastHandout = $person->lastCouponHandout($coupon);
                $mapping[] = $lastHandout ?? '';
            } else {
                $mapping[] = 'x';
            }
        }
        $mapping[] = $person->remarks;
        return $mapping;
    }

    protected function applyStyles(Worksheet $sheet)
    {
        parent::applyStyles($sheet);

        // Horizontally center coupon handout date columns
        $col = Coordinate::stringFromColumnIndex(9 + $this->couponTypes->count());
        $sheet->getStyle('J2:'.$col.$sheet->getHighestRow())->getAlignment()->setHorizontal('center');
    }
}
