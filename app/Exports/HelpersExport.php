<?php

namespace App\Exports;

use App\Helper;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithMapping;

class HelpersExport extends BaseExport implements FromCollection, WithMapping
{
    private $fields;
    private $scopeMethod;
    private $sorting = 'person.name';

    public function __construct(Collection $fields, string $scopeMethod) {
        $this->fields = $fields;
        $this->scopeMethod = $scopeMethod;
    }

    public function setSorting(string $sorting) {
        $this->sorting = $sorting;
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
                    $value = $helper->{$field['value']};
                } else {
                    $value = $field['value']($helper);
                }
                return $value != null ? (($field['prefix'] ?? '') . $value) : null;
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
