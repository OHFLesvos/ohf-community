<?php

namespace App\Imports\CommunityVolunteers;

use App\Models\CommunityVolunteers\CommunityVolunteer;
use App\Models\CommunityVolunteers\Responsibility;
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

        $this->has_dates = $this->fields->where('key', 'app.starting_date')->first() != null
            && $this->fields->where('key', 'app.leaving_date')->first() != null;
    }

    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {
            $cmtyvol = new CommunityVolunteer();
            $this->assignImportedValues($row, $cmtyvol, false);

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
                    // assign responsibilities
                    $this->assignImportedValues($row, $cmtyvol);
                }
            }
        }
    }

    private function assignImportedValues($row, CommunityVolunteer $cmtyvol, bool $assign_responsibilities = true)
    {
        $responsibilities = [];
        $row->each(function ($value, $label) use ($cmtyvol, &$responsibilities) {
            if ($value == 'N/A') {
                $value = null;
            }
            $this->fields->each(function ($f) use ($cmtyvol, $label, $value, &$responsibilities) {
                if ($f['labels']->containsStrict(strtolower($label))) {
                    try {
                        if ($f['key'] == 'app.responsibilities') {
                            if ($value != null) {
                                foreach (preg_split('/(\s*[,;|]\s*)/', $value) as $responsibility) {
                                    $responsibilities[] = $responsibility;
                                }
                            }
                        } elseif ($f['append']) {
                            self::appendToField($cmtyvol, $f['get'], $f['assign'], $value);
                        } else {
                            $f['assign']($cmtyvol, $value);
                        }
                    } catch (Exception $e) {
                        Log::warning('Cannot import community volunteer: ' . $e->getMessage());
                    }
                }
            });
        });

        if ($assign_responsibilities) {
            foreach ($responsibilities as $responsibility_name) {
                $responsibility = Responsibility::updateOrCreate([ 'name' => $responsibility_name ]);
                $from = $this->has_dates ? $row[__('app.starting_date')] : null;
                $to = $this->has_dates ? $row[__('app.leaving_date')] : null;
                if (!$cmtyvol->responsibilities()->wherePivot('start_date', $from)->wherePivot('end_date', $to)->find($responsibility) != null) {
                    $cmtyvol->responsibilities()->attach($responsibility, [
                        'start_date' => $from,
                        'end_date' => $to,
                    ]);
                }
            }
        }
    }

    private static function appendToField(CommunityVolunteer $cmtyvol, $get, $assign, $value)
    {
        $old_value = is_callable($get) ? $get($cmtyvol) : $cmtyvol[$get];
        $assign($cmtyvol, $value);

        if ($old_value != null) {
            $new_value = is_callable($get) ? $get($cmtyvol) : $cmtyvol[$get];

            if (is_array($old_value)) {
                $assign($cmtyvol, array_merge($old_value, $new_value));
            } elseif (is_string($old_value)) {
                $assign($cmtyvol, $old_value . ', ' . $new_value);
            } elseif (is_object($old_value) && get_class($old_value) == 'Illuminate\Database\Eloquent\Collection') {
                $assign($cmtyvol, $old_value->concat($new_value));
            } else {
                Log::warning('Cannot append value of type: ' . gettype($old_value));
            }
        }
    }
}
