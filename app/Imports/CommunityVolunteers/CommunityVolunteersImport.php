<?php

namespace App\Imports\CommunityVolunteers;

use App\Models\CommunityVolunteers\CommunityVolunteer;
use App\Models\People\Person;
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
            $person = new Person();
            $cmtyvol = new CommunityVolunteer();

            $this->assignImportedValues($row, $cmtyvol, $person);

            if (isset($person->name) && isset($person->family_name)) {
                $existing_person = Person::where('name', $person->name)
                    ->where('family_name', $person->family_name)
                    ->where('nationality', $person->nationality)
                    ->where('date_of_birth', $person->date_of_birth)
                    ->first();
                if ($existing_person != null) {
                    $existing_cmtyvol = $existing_person->helper;
                    $new_cmtyvol = false;
                    if ($existing_cmtyvol == null) {
                        $existing_cmtyvol = new CommunityVolunteer();
                        $new_cmtyvol = true;
                    }
                    $this->assignImportedValues($row, $existing_cmtyvol, $existing_person);
                    $existing_person->save();
                    if ($new_cmtyvol) {
                        $existing_person->helper()->save($existing_cmtyvol);
                    } else {
                        $existing_cmtyvol->save();
                    }
                } else {
                    $person->save();
                    $person->helper()->save($cmtyvol);
                }
            }
        }
    }

    private function assignImportedValues($row, $cmtyvol, $person)
    {
        $row->each(function ($value, $label) use ($cmtyvol, $person) {
            if ($value == 'N/A') {
                $value = null;
            }
            $this->fields->each(function ($f) use ($cmtyvol, $person, $label, $value) {
                if ($f['labels']->containsStrict(strtolower($label))) {
                    try {
                        $f['assign']($person, $cmtyvol, $value);
                    } catch (Exception $e) {
                        Log::warning('Cannot import community volunteer: ' . $e->getMessage());
                    }
                }
            });
        });
    }
}
