<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithMapping;

use Illuminate\Support\Collection;

use App\Helper;

class HelpersExport extends BaseExport implements FromCollection, WithMapping
{
    private $fields;
    private $scopeMethod;
    private $sorting = 'person.name'; // TODO flexible sorting

    public function __construct(Collection $fields, string $scopeMethod) {
        $this->fields = $fields;
        $this->scopeMethod = $scopeMethod;
    }

    public function collection(): Collection
    {
        return Helper::{$this->scopeMethod}()
            ->get()
            ->load('person')
            ->sortBy($this->sorting);
    }

    /**
    * @var Helper $helper
    * @return array
    */
    public function map($helper): array
    {
        return $this->fields
            ->map(function($field) use($helper) {
                if (gettype($field['value']) == 'string') {
                    return ($field['prefix'] ?? '').($helper->{$field['value']});
                } else {
                    return ($field['prefix'] ?? '').($field['value']($helper));
                }
            })
            ->toArray();
    }

    /**
     * @return string
     */
    public function title(): string
    {
        return __('people.helpers');
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        return $this->fields
            ->map(function($field){
                return __($field['label_key']);
            })
            ->toArray();
    }
}
