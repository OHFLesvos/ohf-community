<?php

namespace App\Exports\CommunityVolunteers;

use App\Exports\BaseExport;
use App\Models\CommunityVolunteers\CommunityVolunteer;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class CommunityVolunteersExport extends BaseExport implements FromCollection, WithHeadings, WithMapping
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
        return CommunityVolunteer::{$this->scopeMethod}()
            ->get()
            ->load('person')
            ->sortBy($this->sorting);
    }

    /**
     * @param CommunityVolunteer $communityVolunteer
     */
    public function map($communityVolunteer): array
    {
        return $this->fields
            ->map(fn ($field) => self::mapField($field, $communityVolunteer))
            ->toArray();
    }

    private static function mapField($field, $communityVolunteer)
    {
        if (gettype($field['value']) == 'string') {
            $value = $communityVolunteer->{$field['value']};
        } else {
            $value = $field['value']($communityVolunteer);
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
        return __('cmtyvol.community_volunteers');
    }

    public function headings(): array
    {
        return $this->fields
            ->map(fn ($field) => __($field['label_key']))
            ->toArray();
    }
}
