<?php

namespace Modules\Helpers\Imports;

use App\Person;

use Modules\Helpers\Entities\Helper;

use Illuminate\Support\Collection;

use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Imports\HeadingRowFormatter;

class HelpersImport implements ToCollection, WithHeadingRow
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
            $helper = new Helper();

            $this->assignImportedValues($row, $helper, $person);

            if (isset($person->name) && isset($person->family_name)) {
                $existing_person = Person::where('name', $person->name)
                    ->where('family_name', $person->family_name)
                    ->where('nationality', $person->nationality)
                    ->where('date_of_birth', $person->date_of_birth)
                    ->first();
                if ($existing_person != null) {
                    $existing_helper = $existing_person->helper;
                    $new_helper = false;
                    if ($existing_helper == null) {
                        $existing_helper = new Helper();
                        $new_helper = true;
                    }
                    $this->assignImportedValues($row, $existing_helper, $existing_person);
                    $existing_person->save();
                    if ($new_helper) {
                        $existing_person->helper()->save($existing_helper);
                    } else {
                        $existing_helper->save();
                    }
                } else {
                    $person->save();
                    $person->helper()->save($helper);
                }
            }
        }
    }

    private function assignImportedValues($row, $helper, $person) {
        $row->each(function($value, $label) use($helper, $person) {
            if ($value == 'N/A') {
                $value = null;
            }
            $this->fields->each(function($f) use($helper, $person, $label, $value) {
                if ($f['labels']->containsStrict(strtolower($label))) {
                    try {
                        $f['assign']($person, $helper, $value);
                    } catch(\Exception $e) {
                        // ignored
                    }
                }
            });
        });
    }
}
