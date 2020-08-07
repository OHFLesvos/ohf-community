<?php

namespace App\Imports\CommunityVolunteers;

use App\Models\CommunityVolunteers\CommunityVolunteer;
use Exception;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Imports\HeadingRowFormatter;

class CommunityVolunteersImport implements ToCollection, WithHeadingRow
{
    use Importable;

    private $fields;

    public function __construct(Collection $fields)
    {
        HeadingRowFormatter::default('none');

        $this->fields = $fields;
    }

    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {
            $cmtyvol = new CommunityVolunteer();
            $this->assignImportedValues($row, $cmtyvol);

            if (isset($cmtyvol->first_name) && isset($cmtyvol->family_name)) {
                $existing_cmtyvol = CommunityVolunteer::query()
                    ->where('first_name', $cmtyvol->first_name)
                    ->where('family_name', $cmtyvol->family_name)
                    ->where('nationality', $cmtyvol->nationality)
                    ->where('date_of_birth', $cmtyvol->date_of_birth)
                    ->first();
                if ($existing_cmtyvol !== null) {
                    $this->assignImportedValues($row, $existing_cmtyvol);
                    $existing_cmtyvol->save();
                } else {
                    $cmtyvol->save();
                }
            }
        }
    }

    private function assignImportedValues($row, CommunityVolunteer $cmtyvol)
    {
        $row->each(function ($value, $label) use ($cmtyvol) {
            if ($value == 'N/A') {
                $value = null;
            }
            $this->fields->each(function ($f) use ($cmtyvol, $label, $value) {
                if ($f['labels']->containsStrict(strtolower($label))) {
                    try {
                        $f['assign']($cmtyvol, $value);
                    } catch (Exception $e) {
                        Log::warning('Cannot import community volunteer: ' . $e->getMessage());
                    }
                }
            });
        });
    }
}
