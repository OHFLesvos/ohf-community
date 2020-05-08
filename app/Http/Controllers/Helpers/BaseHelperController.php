<?php

namespace App\Http\Controllers\Helpers;

use App\Http\Controllers\Controller;
use App\Models\Helpers\Helper;
use App\Models\Helpers\Responsibility;
use App\Models\People\Person;
use Carbon\Carbon;
use Countries;
use Gumlet\ImageResize;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

abstract class BaseHelperController extends Controller
{
    protected function getSections()
    {
        return [
            'portrait' => __('people.portrait'),
            'general' => __('app.general'),
            'reachability' => __('people.reachability'),
            'occupation' => __('people.occupation'),
            'identification' => __('people.identification'),
            'distribution' => __('people.distribution'),
        ];
    }

    protected function getFields() {
        return [
            [
                'label_key' => 'people.portrait_picture',
                'icon' => null,
                'value' => fn ($helper) => $helper->person->portrait_picture,
                'value_html' => fn ($helper) => isset($helper->person->portrait_picture) ? '<img src="' . Storage::url($helper->person->portrait_picture) . '" class="img-fluid img-thumbnail">' : null,
                'overview' => false,
                'exclude_export' => true,
                'exclude_show' => false,
                'section' => 'portrait',
                'assign' => function ($person, $helper, $value) {
                    if (isset($value)) {
                        if ($person->portrait_picture != null) {
                            Storage::delete($person->portrait_picture);
                        }
                        $image = new ImageResize($value->getRealPath());
                        $image->resizeToBestFit(800, 800, true);
                        $image->crop(533, 800, true); // 2:3 aspect ratio
                        $image->save($value->getRealPath());
                        $person->portrait_picture = $value->store('public/people/portrait_pictures');
                    }
                },
                'cleanup' => function ($person, $helper) {
                    if ($person->portrait_picture != null) {
                        Storage::delete($person->portrait_picture);
                        $person->portrait_picture = null;
                    }
                },
                'form_type' => 'image',
                'form_name' => 'portrait_picture',
                'form_validate' => [
                    'nullable',
                    'image',
                ],
                'form_help' => __('people.image_will_be_croped_resized_to_2_3_aspect_ratio'),
            ],
            [
                'label_key' => 'people.name',
                'icon' => null,
                'value' => fn ($helper) => $helper->person->name,
                'overview' => true,
                'detail_link' => true,
                'section' => 'general',
                'assign' => function ($person, $helper, $value) {
                    $person->name = $value;
                },
                'form_type' => 'text',
                'form_name' => 'name',
                'form_validate' => [
                    'required',
                    'max:191',
                ],
            ],
            [
                'label_key' => 'people.family_name',
                'icon' => null,
                'value' => fn ($helper) => $helper->person->family_name,
                'overview' => true,
                'section' => 'general',
                'import_labels' => [ 'Surname' ],
                'assign' => function ($person, $helper, $value) {
                    $person->family_name = $value;
                },
                'form_type' => 'text',
                'form_name' => 'family_name',
                'form_validate' => [
                    'required',
                    'max:191',
                ],
            ],
            [
                'label_key' => 'people.nickname',
                'icon' => null,
                'value' => fn ($helper) => $helper->person->nickname,
                'overview' => true,
                'section' => 'general',
                'assign' => function ($person, $helper, $value) {
                    $person->nickname = $value;
                },
                'form_type' => 'text',
                'form_name' => 'nickname',
                'form_validate' => [
                    'nullable',
                    'max:191',
                ],
            ],
            [
                'label_key' => 'people.nationality',
                'icon' => 'globe',
                'value' => fn ($helper) => $helper->person->nationality,
                'overview' => true,
                'section' => 'general',
                'assign' => function ($person, $helper, $value) {
                    $person->nationality = $value;
                },
                'form_type' => 'text',
                'form_name' => 'nationality',
                'form_autocomplete' => fn () => Countries::getList('en'),
                'form_validate' => fn () => [
                    'nullable',
                    'max:191',
                    Rule::in(Countries::getList('en')),
                ],
            ],
            [
                'label_key' => 'people.gender',
                'icon' => null,
                'value' => fn ($helper) => $helper->person->gender != null ? ($helper->person->gender == 'f' ? __('app.female') : __('app.male')) : null,
                'value_html' => fn ($helper) => $helper->person->gender != null ? ($helper->person->gender == 'f' ? icon('female') : icon('male')) : null,
                'overview' => true,
                'section' => 'general',
                'assign' => function ($person, $helper, $value) {
                    $person->gender = ($value != null ? (self::getAllTranslations('app.female')->contains($value) ? 'f' : 'm') : null);
                },
                'form_type' => 'radio',
                'form_name' => 'gender',
                'form_list' => [
                    null => __('app.unspecified'),
                    __('app.male') => __('app.male'),
                    __('app.female') => __('app.female'),
                ],
                'form_validate' => 'required', // TODO better validation |in:m,f
            ],
            [
                'label_key' => 'people.date_of_birth',
                'icon' => null,
                'value' => fn ($helper) => $helper->person->date_of_birth,
                'overview' => false,
                'section' => 'general',
                'import_labels' => [ 'DOB' ],
                'assign' => function ($person, $helper, $value) {
                    $person->date_of_birth = ! empty($value) ? Carbon::parse($value) : null;
                },
                'form_type' => 'text',
                'form_name' => 'date_of_birth',
                'form_placeholder' => 'YYYY-MM-DD',
                'form_validate' => 'required|date',
            ],
            [
                'label_key' => 'people.age',
                'icon' => null,
                'value' => fn ($helper) => $helper->person->age,
                'overview' => true,
                'section' => 'general',
            ],
            [
                'label_key' => 'people.languages',
                'icon' => 'language',
                'value' => fn ($helper) => $helper->person->languages != null ? (is_array($helper->person->languages) ? implode(', ', $helper->person->languages) : $helper->person->languages) : null,
                'value_html' => fn ($helper) => $helper->person->languages != null ? (is_array($helper->person->languages) ? implode('<br>', $helper->person->languages) : $helper->person->languages) : null,
                'overview' => false,
                'section' => 'general',
                'assign' => function ($person, $helper, $value) {
                    $person->languages = ($value != null ? array_map('trim', preg_split('/(\s*[,\/|]\s*)|(\s+and\s+)/', $value)) : null);
                },
                'form_type' => 'text',
                'form_name' => 'languages',
                'form_help' => __('app.separate_by_comma'),
                'form_autocomplete' => fn () => Person::languages(),
            ],
            [
                'label_key' => 'app.local_phone',
                'icon' => 'phone',
                'value' => 'local_phone',
                'value_html' => fn ($helper) => $helper->local_phone != null ? tel_link($helper->local_phone) : null,
                'overview' => false,
                'section' => 'reachability',
                'import_labels' => [ 'Greek No.' ],
                'assign' => function ($person, $helper, $value) {
                    $helper->local_phone = $value;
                },
                'form_type' => 'text',
                'form_name' => 'local_phone',
            ],
            [
                'label_key' => 'app.other_phone',
                'icon' => 'phone',
                'value' => 'other_phone',
                'value_html' => fn ($helper) => $helper->other_phone != null ? tel_link($helper->other_phone) : null,
                'overview' => false,
                'section' => 'reachability',
                'import_labels' => [ 'Other No.' ],
                'assign' => function ($person, $helper, $value) {
                    $helper->other_phone = $value;
                },
                'form_type' => 'text',
                'form_name' => 'other_phone',
            ],
            [
                'label_key' => 'app.whatsapp',
                'icon' => 'whatsapp',
                'value' => 'whatsapp',
                'value_html' => fn ($helper) => $helper->whatsapp != null ? whatsapp_link($helper->whatsapp, 'Hello ' . $helper->person->name . "\n") : null,
                'overview' => false,
                'section' => 'reachability',
                'assign' => function ($person, $helper, $value) {
                    $helper->whatsapp = ($value == 'same' ? $helper->local_phone : $value);
                },
                'form_type' => 'text',
                'form_name' => 'whatsapp',
            ],
            [
                'label_key' => 'app.email',
                'icon' => 'envelope',
                'value' => 'email',
                'value_html' => fn ($helper) => $helper->email != null ? email_link($helper->email) : null,
                'overview' => false,
                'section' => 'reachability',
                'assign' => function ($person, $helper, $value) {
                    $helper->email = $value;
                },
                'form_type' => 'email',
                'form_name' => 'email',
                'form_validate' => 'nullable|email',
            ],
            [
                'label_key' => 'app.skype',
                'icon' => 'skype',
                'value' => 'skype',
                'value_html' => fn ($helper) => $helper->skype != null ? skype_link($helper->skype) : null,
                'overview' => false,
                'section' => 'reachability',
                'assign' => function ($person, $helper, $value) {
                    $helper->skype = $value;
                },
                'form_type' => 'text',
                'form_name' => 'skype',
            ],
            [
                'label_key' => 'people.residence',
                'icon' => null,
                'value' => 'residence',
                'value_html' => fn ($helper) => nl2br($helper->residence),
                'overview' => false,
                'section' => 'reachability',
                'assign' => function ($person, $helper, $value) {
                    $helper->residence = $value;
                },
                'form_type' => 'textarea',
                'form_name' => 'residence',
            ],
            [
                'label_key' => 'people.pickup_location',
                'icon' => null,
                'value' => 'pickup_location',
                'value_html' => fn ($helper) => nl2br($helper->pickup_location),
                'overview' => false,
                'section' => 'reachability',
                'assign' => function ($person, $helper, $value) {
                    $helper->pickup_location = $value;
                },
                'form_type' => 'text',
                'form_name' => 'pickup_location',
                'form_autocomplete' => fn () => Helper::pickupLocations(),
            ],
            [
                'label_key' => 'app.responsibilities',
                'icon' => null,
                'value' => fn ($helper) => $helper->responsibilities->pluck('name')->all(),
                'value_html' => fn ($helper) => $helper->responsibilities
                    ->map(function ($r) {
                        $str = $r->name;
                        if ($r->hasHelpersAltoughNotAvailable) {
                            $str .= ' <span class="text-danger">(' . __('app.not_available') . ')</span>';
                        }
                        if ($r->isCapacityExhausted) {
                            $str .= ' <span class="text-danger">(' . __('app.capacity_exhausted') . ')</span>';
                        }
                        return $str;
                    })
                    ->implode('<br>'),
                'overview' => true,
                'section' => 'occupation',
                'import_labels' => [ 'Project' ],
                'assign' => function ($person, $helper, $value) {
                    $selected = [];
                    if ($value != null) {
                        if (! is_array($value)) {
                            $values = [];
                            foreach (preg_split('/(\s*[,\/|]\s*)|(\s+and\s+)/', $value) as $v) {
                                $values[] = $v;
                            }
                            $value = array_map('trim', $values);
                        }
                        $selected = Responsibility::whereIn('name', $value)
                            ->get()
                            ->pluck('id')
                            ->all();
                    }
                    $helper->responsibilities()->sync($selected);
                },
                'form_type' => 'checkboxes',
                'form_name' => 'responsibilities',
                'form_list' => Responsibility::where('available', true)
                    ->orderBy('name')
                    ->get()
                    ->filter(fn ($responsibility) => ! $responsibility->isCapacityExhausted)
                    ->pluck('name', 'name')
                    ->toArray(),
                'form_validate' => fn () => [
                    Rule::in(Responsibility::select('name')->get()->pluck('name')->all()),
                ],
            ],
            [
                'label_key' => 'people.starting_date',
                'icon' => 'calendar',
                'value' => fn ($helper) => optional($helper->work_starting_date)->toDateString(),
                'overview' => false,
                'section' => 'occupation',
                'import_labels' => [ 'Starting date at OHF' ],
                'assign' => function ($person, $helper, $value) {
                    $helper->work_starting_date = ! empty($value) ? Carbon::parse($value) : null;
                },
                'form_type' => 'date',
                'form_name' => 'starting_date',
                'form_validate' => 'nullable|date',
            ],
            [
                'label_key' => 'helpers.working_since_days',
                'icon' => null,
                'value' => fn ($helper) => $helper->working_since_days,
                'overview' => false,
                'section' => 'occupation',
                'form_name' => 'working_since_days',
            ],
            [
                'label_key' => 'people.leaving_date',
                'icon' => 'calendar',
                'value' => fn ($helper) => optional($helper->work_leaving_date)->toDateString(),
                'overview' => false,
                'section' => 'occupation',
                'assign' => function ($person, $helper, $value) {
                    $helper->work_leaving_date = ! empty($value) ? Carbon::parse($value) : null;
                },
                'form_type' => 'date',
                'form_name' => 'leaving_date',
                'form_validate' => 'nullable|date',
            ],
            [
                'label_key' => 'people.police_number',
                'icon' => 'id-card',
                'value' => fn ($helper) => $helper->person->police_no,
                'overview' => false,
                'prefix' => '05/',
                'section' => 'identification',
                'import_labels' => [ 'Police No.' ],
                'assign' => function ($person, $helper, $value) {
                    $val = preg_replace('/^05\//', '', $value);
                    $person->police_no = (! empty($val) ? $val : null);
                },
                'form_type' => 'number',
                'form_name' => 'police_number',
                'form_validate' => 'nullable|numeric',
            ],
            [
                'label_key' => 'people.notes',
                'icon' => null,
                'value' => fn ($helper) => $helper->notes,
                'value_html' => fn ($helper) => nl2br($helper->notes),
                'overview' => false,
                'section' => 'general',
                'import_labels' => [ 'Notes' ],
                'assign' => function ($person, $helper, $value) {
                    $helper->notes = $value;
                },
                'form_type' => 'textarea',
                'form_name' => 'notes',
            ],
            [
                'label_key' => 'people.remarks',
                'icon' => null,
                'value' => fn ($helper) => $helper->person->remarks,
                'value_html' => fn ($helper) => nl2br($helper->person->remarks),
                'overview' => false,
                'section' => 'distribution',
                'assign' => function ($person, $helper, $value) {
                    $person->remarks = $value;
                },
                'form_type' => 'textarea',
                'form_name' => 'remarks',
            ],
        ];
    }

    protected function getScopes()
    {
        return collect([
            'active' => [
                'label' => __('people.active'),
                'scope' => 'active',
            ],
            'future' => [
                'label' => __('people.future'),
                'scope' => 'future',
            ],
            'alumni' => [
                'label' => __('people.alumni'),
                'scope' => 'alumni',
            ],
        ]);
    }

    protected function getGroupings()
    {
        return collect([
            'nationalities' => [
                'label' => __('people.nationalities'),
                'groups' => fn () => Person::nationalities(),
                'query' => fn ($query, $value) => $query
                    ->select('helpers.*')
                    ->join('persons', 'helpers.person_id', '=', 'persons.id')
                    ->where('nationality', $value),
            ],
            'languages' => [
                'label' => __('people.languages'),
                'groups' => fn () => Person::languages(),
                'query' => fn ($query, $value) => $query
                    ->select('helpers.*')
                    ->join('persons', 'helpers.person_id', '=', 'persons.id')
                    ->where('languages', 'like', '%"' . $value . '"%'),
            ],
            'gender' => [
                'label' => __('people.gender'),
                'groups' => fn () => Person::genders(),
                'query' => fn ($query, $value) => $query
                    ->select('helpers.*')
                    ->join('persons', 'helpers.person_id', '=', 'persons.id')
                    ->where('gender', $value),
                'label_transform' => fn ($groups) => collect($groups)
                    ->map(function ($s) {
                        switch ($s) {
                            case 'f':
                                return __('app.female');
                            case 'm':
                                return __('app.male');
                            default:
                                return __('app.unspecified');
                        }
                    }),
            ],
            'responsibilities' => [
                'label' => __('app.responsibilities'),
                'groups' => fn () => Responsibility::has('helpers')
                    ->orderBy('name')
                    ->get()
                    ->pluck('name')
                    ->toArray(),
                'query' => fn ($q, $v) => $q->whereHas('responsibilities', function (Builder $query) use ($v) {
                    $query->where('name', $v);
                }),
            ],
            'pickup_locations' => [
                'label' => __('people.pickup_locations'),
                'groups' => fn () => Helper::groupBy('pickup_location')
                    ->orderBy('pickup_location')
                    ->whereNotNull('pickup_location')
                    ->get()
                    ->pluck('pickup_location')
                    ->unique()
                    ->sort()
                    ->push(null)
                    ->toArray(),
                'query' => fn ($query, $value) => $query
                    ->select('helpers.*')
                    ->join('persons', 'helpers.person_id', '=', 'persons.id')
                    ->where('pickup_location', $value),
                'label_transform' => fn ($groups) => collect($groups)
                    ->map(fn ($s) => $s == null ? __('app.unspecified') : $s),
            ],
        ]);
    }

    protected function getViews()
    {
        return collect([
            'list' => [
                'label' => __('app.list'),
                'icon' => 'list',
            ],
            'grid' => [
                'label' => __('app.grid'),
                'icon' => 'th-large',
            ],
        ]);
    }

    protected function getColumnSets() {
        return collect([
            'all' => [
                'label' => __('app.all'),
                'columns' => [],
            ],
            'name_nationality_occupation' => [
                'label' => __('people.name_nationality_occupation'),
                'columns' => ['name', 'family_name', 'nickname', 'nationality', 'responsibilities'],
            ],
            'contact_info' => [
                'label' => __('people.contact_info'),
                'columns' => ['name', 'family_name', 'nickname', 'local_phone', 'other_phone', 'whatsapp', 'email', 'skype', 'residence'],
            ],
        ]);
    }

    protected function getSorters() {
        return collect([
            'first_name' => [
                'label' => __('app.first_name'),
                'sorting' => 'person.name',
            ],
            'last_name' => [
                'label' => __('app.last_name'),
                'sorting' => 'person.family_name',
            ],
            'nationality' => [
                'label' => __('people.nationality'),
                'sorting' => 'person.nationality',
            ],
            'gender' => [
                'label' => __('people.gender'),
                'sorting' => 'person.gender',
            ],
            'age' => [
                'label' => __('people.age'),
                'sorting' => 'person.age',
            ],
            'residence' => [
                'label' => __('people.residence'),
                'sorting' => 'residence',
            ],
            'pickup_location' => [
                'label' => __('people.pickup_location'),
                'sorting' => 'pickup_location',
            ],
            'work_starting_date' => [
                'label' => __('people.starting_date'),
                'sorting' => 'work_starting_date',
            ],
        ]);
    }

    protected static function getAllTranslations($key) {
        return collect(language()->allowed())
            ->keys()
            ->map(fn ($lk) => __($key, [], $lk));
    }

}
