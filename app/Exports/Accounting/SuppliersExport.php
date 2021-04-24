<?php

namespace App\Exports\Accounting;

use App\Exports\BaseExport;
use App\Models\Accounting\Supplier;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class SuppliersExport extends BaseExport implements FromQuery, WithHeadings, WithMapping
{
    public function __construct()
    {
        $this->orientation = 'landscape';
    }

    public function query(): \Illuminate\Database\Eloquent\Builder
    {
        return Supplier::query()
            ->orderBy('name', 'asc');
    }

    public function title(): string
    {
        return __('app.suppliers');
    }

    public function headings(): array
    {
        return [
            __('app.name'),
            __('app.category'),
            __('app.address'),
            __('app.phone'),
            __('app.mobile'),
            __('app.email'),
            __('app.website'),
            __('app.tax_number'),
            __('app.tax_office'),
            __('app.bank'),
            __('app.iban'),
            __('app.transactions'),
            __('app.spending'),
            __('app.remarks'),
        ];
    }

    /**
     * @param Supplier $supplier
     */
    public function map($supplier): array
    {
        return [
            $supplier->name,
            $supplier->category,
            $supplier->address,
            $supplier->phone,
            $supplier->mobile,
            $supplier->email,
            $supplier->website,
            $supplier->tax_number,
            $supplier->tax_office,
            $supplier->bank,
            $supplier->iban,
            $supplier->transactions()->count(),
            $supplier->transactions()->where('type', 'spending')->sum('amount'),
            $supplier->remarks
        ];
    }
}
