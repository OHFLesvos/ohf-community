<?php

namespace Modules\Bank\Exports;

use App\Person;
use App\CouponType;
use App\Exports\BaseExport;

use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;

class BankExport extends BaseExport implements FromQuery, WithHeadings, WithMapping
{
    private $couponTypes;

    public function __construct()
    {
        $this->setOrientation('landscape');

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

    /**
     * @return string
     */
    public function title(): string
    {
        return __('people::people.withdrawals');
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        $headings = [
            __('people::people.id'),
            __('people::people.family_name'),
            __('people::people.name'),
            __('people::people.date_of_birth'),
            __('people::people.age'),
            __('people::people.nationality'),
            __('people::people.police_number'),
            __('people::people.registration_number'),
            __('people::people.section_card_number'),
        ];
        foreach($this->couponTypes as $coupon) {
            $headings[] = $coupon->name;
        }
        $headings[] = __('people::people.remarks');
        return $headings;
    }

    /**
    * @var Person $person
    */
    public function map($person): array
    {
        $mapping = [
            $person->id,
            $person->family_name,
            $person->name,
            $person->date_of_birth,
            $person->age,
            $person->nationality,
            $person->police_no,
            $person->registration_no,
            $person->section_card_no,
        ];
        foreach($this->couponTypes as $coupon) {
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
