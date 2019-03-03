<?php

namespace App\Exports;

use App\MoneyTransaction;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;

class MoneyTransactionsExport extends BaseExport implements FromCollection
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection(): Collection
    {
        return MoneyTransaction::all();
    }

    /**
     * @return string
     */
    public function title(): string
    {
        return ...;
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        return ...;
    }
}
