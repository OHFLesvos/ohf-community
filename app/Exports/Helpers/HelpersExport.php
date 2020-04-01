<?php

namespace App\Exports\Helpers;

use App\Exports\BaseExport;
use App\Models\Helpers\Helper;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class HelpersExport extends BaseExport implements FromCollection, WithHeadings, WithMapping
{
    /**
     * Field definitions
     */
    private Collection $fields;

    /**
     * Scope method name
     */
    private string $scopeMethod;

    /**
     * Field to sort by
     */
    public string $sorting = 'person.name';

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
     * @param Helper $helper
     */
    public function map($helper): array
    {
        return $this->fields
            ->map(fn ($field) => self::mapField($field, $helper))
            ->toArray();
    }

    private static function mapField($field, $helper)
    {
        if (gettype($field['value']) == 'string') {
            $value = $helper->{$field['value']};
        } else {
            $value = $field['value']($helper);
        }
        if ($value !== null) {
            $prefix = $field['prefix'] ?? '';
            $valueString = is_array($value) ? implode(', ', $value) : $value;
            return $prefix . $valueString;
        }
        return null;
    }

    public function title(): string
    {
        return __('helpers.helpers');
    }

    public function headings(): array
    {
        return $this->fields
            ->map(fn ($field) => __($field['label_key']))
            ->toArray();
    }
}
