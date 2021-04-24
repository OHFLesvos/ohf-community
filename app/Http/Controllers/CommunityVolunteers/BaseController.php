<?php

namespace App\Http\Controllers\CommunityVolunteers;

use App\Http\Controllers\Controller;
use App\Models\CommunityVolunteers\CommunityVolunteer;
use App\Models\CommunityVolunteers\Responsibility;
use Carbon\Carbon;
use Countries;
use Gumlet\ImageResize;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\View\ComponentAttributeBag;

abstract class BaseController extends Controller
{
    protected function getSections()
    {
        return [
            'portrait' => __('people.portrait'),
            'general' => __('app.general'),
            'reachability' => __('people.reachability'),
            'occupation' => __('people.occupation'),
        ];
    }

    protected function getFields()
    {
        return [
            [
                'label_key' => 'people.portrait_picture',
                'icon' => null,
                'value' => fn ($cmtyvol) => $cmtyvol->portrait_picture,
                'value_html' => fn ($cmtyvol) => isset($cmtyvol->portrait_picture) ? '<img src="' . Storage::url($cmtyvol->portrait_picture) . '" class="img-fluid img-thumbnail">' : null,
                'overview' => false,
                'exclude_export' => true,
                'exclude_show' => false,
                'section' => 'portrait',
                'assign' => function ($cmtyvol, $value) {
                    if (isset($value)) {
                        if ($cmtyvol->portrait_picture != null) {
                            Storage::delete($cmtyvol->portrait_picture);
                        }
                        $image = new ImageResize($value->getRealPath());
                        $image->resizeToBestFit(800, 800, true);
                        $image->crop(533, 800, true); // 2:3 aspect ratio
                        $image->save($value->getRealPath());
                        $cmtyvol->portrait_picture = $value->store('public/people/portrait_pictures');
                    }
                },
                'cleanup' => function ($cmtyvol) {
                    if ($cmtyvol->portrait_picture != null) {
                        Storage::delete($cmtyvol->portrait_picture);
                        $cmtyvol->portrait_picture = null;
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
                'label_key' => 'people.first_name',
                'icon' => null,
                'value' => fn ($cmtyvol) => $cmtyvol->first_name,
                'overview' => true,
                'detail_link' => true,
                'section' => 'general',
                'assign' => function ($cmtyvol, $value) {
                    $cmtyvol->first_name = $value;
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
                'value' => fn ($cmtyvol) => $cmtyvol->family_name,
                'overview' => true,
                'section' => 'general',
                'import_labels' => ['Surname'],
                'assign' => function ($cmtyvol, $value) {
                    $cmtyvol->family_name = $value;
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
                'value' => fn ($cmtyvol) => $cmtyvol->nickname,
                'overview' => true,
                'section' => 'general',
                'assign' => function ($cmtyvol, $value) {
                    $cmtyvol->nickname = $value;
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
                'value' => fn ($cmtyvol) => $cmtyvol->nationality,
                'overview' => true,
                'section' => 'general',
                'assign' => function ($cmtyvol, $value) {
                    $cmtyvol->nationality = $value;
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
                'value' => fn ($cmtyvol) => $cmtyvol->gender != null ? ($cmtyvol->gender == 'f' ? __('app.female') : __('app.male')) : null,
                'value_html' => fn ($cmtyvol) => $cmtyvol->gender != null
                    ? view('components.icon-gender', [
                        'gender' => $cmtyvol->gender,
                        'attributes' => new ComponentAttributeBag(),
                    ]) : null,
                'overview' => true,
                'section' => 'general',
                'assign' => function ($cmtyvol, $value) {
                    $female = self::getAllTranslations('app.female')
                        ->concat(['f', 'w', 'Frau', 'Fräulein', 'Fr.', 'Fr', 'Frl.', 'Frl', 'Missus', 'Missis', 'Miss', 'Mrs.', 'Mrs', 'Ms.', 'Ms'])
                        ->map(fn ($t) => strtolower($t));
                    $male = self::getAllTranslations('app.male')
                        ->concat(['m', 'Herr', 'Hr.', 'Hr', 'Mister', 'Mr.', 'Mr'])
                        ->map(fn ($t) => strtolower($t));

                    if ($female->contains(strtolower($value))) {
                        $cmtyvol->gender = 'f';
                    } elseif ($male->contains(strtolower($value))) {
                        $cmtyvol->gender = 'm';
                    } else {
                        $cmtyvol->gender = null;
                    }
                },
                'form_type' => 'radio',
                'form_name' => 'sex',
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
                'value' => fn ($cmtyvol) => $cmtyvol->date_of_birth,
                'overview' => false,
                'section' => 'general',
                'import_labels' => ['DOB'],
                'assign' => function ($cmtyvol, $value) {
                    $cmtyvol->date_of_birth = !empty($value) ? Carbon::parse($value) : null;
                },
                'form_type' => 'text',
                'form_name' => 'date_of_birth',
                'form_placeholder' => 'YYYY-MM-DD',
                'form_validate' => 'required|date',
            ],
            [
                'label_key' => 'people.age',
                'icon' => null,
                'value' => fn ($cmtyvol) => $cmtyvol->age,
                'overview' => true,
                'section' => 'general',
            ],
            [
                'label_key' => 'people.police_number',
                'icon' => 'id-card',
                'value' => fn ($cmtyvol) => $cmtyvol->police_no,
                'overview' => false,
                'prefix' => '05/',
                'section' => 'general',
                'import_labels' => ['Police No.'],
                'assign' => function ($cmtyvol, $value) {
                    $val = preg_replace('/^05\//', '', $value);
                    $cmtyvol->police_no = (!empty($val) ? $val : null);
                },
                'form_type' => 'number',
                'form_name' => 'police_number',
                'form_validate' => 'nullable|numeric',
            ],
            [
                'label_key' => 'people.languages',
                'icon' => 'language',
                'value' => fn ($cmtyvol) => $cmtyvol->languages != null ? (is_array($cmtyvol->languages) ? implode(', ', $cmtyvol->languages) : $cmtyvol->languages) : null,
                'value_html' => fn ($cmtyvol) => $cmtyvol->languages != null ? (is_array($cmtyvol->languages) ? implode('<br>', $cmtyvol->languages) : $cmtyvol->languages) : null,
                'overview' => false,
                'section' => 'general',
                'assign' => function ($cmtyvol, $value) {
                    $cmtyvol->languages = ($value != null ? array_unique(array_map('trim', preg_split('/(\s*[,;\/|]\s*)|(\s+and\s+)/', $value))) : null);
                },
                'form_type' => 'text',
                'form_name' => 'languages',
                'form_help' => __('app.separate_by_comma'),
                'form_autocomplete' => fn () => CommunityVolunteer::languages(),
            ],
            [
                'label_key' => 'app.local_phone',
                'icon' => 'phone',
                'value' => 'local_phone',
                'value_html' => fn ($cmtyvol) => $cmtyvol->local_phone != null
                    ? view('components.links.tel', [
                        'slot' => $cmtyvol->local_phone,
                        'attributes' => new ComponentAttributeBag(),
                    ]) : null,
                'overview' => false,
                'section' => 'reachability',
                'import_labels' => ['Greek No.'],
                'assign' => function ($cmtyvol, $value) {
                    $cmtyvol->local_phone = $value;
                },
                'form_type' => 'text',
                'form_name' => 'local_phone',
            ],
            [
                'label_key' => 'app.other_phone',
                'icon' => 'phone',
                'value' => 'other_phone',
                'value_html' => fn ($cmtyvol) => $cmtyvol->other_phone != null
                    ? view('components.links.tel', [
                        'slot' => $cmtyvol->other_phone,
                        'attributes' => new ComponentAttributeBag(),
                    ]) : null,
                'overview' => false,
                'section' => 'reachability',
                'import_labels' => ['Other No.'],
                'assign' => function ($cmtyvol, $value) {
                    $cmtyvol->other_phone = $value;
                },
                'form_type' => 'text',
                'form_name' => 'other_phone',
            ],
            [
                'label_key' => 'app.whatsapp',
                'icon' => 'whatsapp',
                'icon_style' => 'fab',
                'value' => 'whatsapp',
                'value_html' => fn ($cmtyvol) => $cmtyvol->whatsapp != null
                    ? view('components.links.whatsapp', [
                        'slot' => $cmtyvol->whatsapp,
                        'message' => 'Hello ' . $cmtyvol->first_name . "\n",
                        'attributes' => new ComponentAttributeBag(),
                    ]) : null,
                'overview' => false,
                'section' => 'reachability',
                'assign' => function ($cmtyvol, $value) {
                    $cmtyvol->whatsapp = ($value == 'same' ? $cmtyvol->local_phone : $value);
                },
                'form_type' => 'text',
                'form_name' => 'whatsapp',
            ],
            [
                'label_key' => 'app.email',
                'icon' => 'envelope',
                'value' => 'email',
                'value_html' => fn ($cmtyvol) => $cmtyvol->email != null
                    ? view('components.links.email', [
                        'slot' => $cmtyvol->email,
                        'attributes' => new ComponentAttributeBag(),
                    ]) : null,
                'overview' => false,
                'section' => 'reachability',
                'assign' => function ($cmtyvol, $value) {
                    $cmtyvol->email = $value;
                },
                'form_type' => 'email',
                'form_name' => 'email',
                'form_validate' => 'nullable|email',
            ],
            [
                'label_key' => 'app.skype',
                'icon' => 'skype',
                'icon_style' => 'fab',
                'value' => 'skype',
                'value_html' => fn ($cmtyvol) => $cmtyvol->skype != null
                    ? view('components.links.skype', [
                        'slot' => $cmtyvol->skype,
                        'attributes' => new ComponentAttributeBag(),
                    ]) : null,
                'overview' => false,
                'section' => 'reachability',
                'assign' => function ($cmtyvol, $value) {
                    $cmtyvol->skype = $value;
                },
                'form_type' => 'text',
                'form_name' => 'skype',
            ],
            [
                'label_key' => 'people.residence',
                'icon' => null,
                'value' => 'residence',
                'value_html' => fn ($cmtyvol) => nl2br($cmtyvol->residence),
                'overview' => false,
                'section' => 'reachability',
                'assign' => function ($cmtyvol, $value) {
                    $cmtyvol->residence = $value;
                },
                'form_type' => 'textarea',
                'form_name' => 'residence',
            ],
            [
                'label_key' => 'people.pickup_location',
                'icon' => null,
                'value' => 'pickup_location',
                'value_html' => fn ($cmtyvol) => nl2br($cmtyvol->pickup_location),
                'overview' => false,
                'section' => 'reachability',
                'assign' => function ($cmtyvol, $value) {
                    $cmtyvol->pickup_location = $value;
                },
                'form_type' => 'text',
                'form_name' => 'pickup_location',
                'form_autocomplete' => fn () => CommunityVolunteer::pickupLocations(),
            ],
            [
                'label_key' => 'app.responsibilities',
                'icon' => null,
                'value' => fn ($cmtyvol) => $cmtyvol->responsibilities
                    ->map(fn ($r) => [
                        'value' => $r->name,
                        'from' => $r->pivot->start_date,
                        'to' => $r->pivot->end_date,
                    ]),
                'value_export' => fn ($cmtyvol) => $cmtyvol->responsibilities()
                    ->orderBy('start_date')
                    ->get()
                    ->map(fn ($r) => [
                        'value' => $r->name,
                        'from' => $r->pivot->start_date,
                        'to' => $r->pivot->end_date,
                    ])
                    ->pluck('value')
                    ->implode('; '),
                'value_html' => fn ($cmtyvol) => $cmtyvol->responsibilities
                    ->map(function ($r) {
                        $str = htmlspecialchars($r->name);
                        if ($r->description !== null) {
                            $str .= ' <a tabindex="0" class="description-tooltip fa fa-info-circle" data-toggle="popover" data-trigger="focus" data-content="' . htmlspecialchars($r->description) . '"></a>';
                        }
                        if ($r->pivot->hasDateRange()) {
                            $str .= ' (' . $r->pivot->date_range_string . ')';
                        }
                        if ($r->hasAssignedAltoughNotAvailable) {
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
                'import_labels' => ['Project'],
                'assign' => function ($cmtyvol, $value) {
                    DB::transaction(function () use ($cmtyvol, $value) {
                        $cmtyvol->responsibilities()->detach();
                        if ($value != null) {
                            if (!is_array($value)) {
                                $values = [];
                                foreach (preg_split('/(\s*[,\/|]\s*)|(\s+and\s+)/', $value) as $v) {
                                    $values[] = $v;
                                }
                                $value = array_map('trim', $values);
                            }

                            collect($value)->map(function ($entry) use ($cmtyvol) {
                                if (!is_array($entry)) {
                                    $entry = ['name' => $entry];
                                }
                                $responsibility = Responsibility::where('name', $entry['name'])->first();
                                $cmtyvol->responsibilities()->attach($responsibility, [
                                    'start_date' => isset($entry['from']) ? $entry['from'] : null,
                                    'end_date' => isset($entry['to']) ? $entry['to'] : null,
                                ]);
                            });
                        }
                    });
                },
            ],
            [
                'label_key' => 'people.starting_date',
                'icon' => null,
                'value' => fn ($cmtyvol) => optional($cmtyvol->firstWorkStartDate)->toDateString(),
                'value_html' => fn ($cmtyvol) => $cmtyvol->firstWorkStartDate != null
                    ? $cmtyvol->firstWorkStartDate->toDateString() . ' (' . $cmtyvol->firstWorkStartDate->diffForHumans() . ')'
                    : null,
                'overview' => false,
                'section' => 'occupation',
            ],
            [
                'label_key' => 'people.leaving_date',
                'icon' => null,
                'value' => fn ($cmtyvol) => optional($cmtyvol->lastWorkEndDate)->toDateString(),
                'value_html' => fn ($cmtyvol) => $cmtyvol->lastWorkEndDate != null
                    ? $cmtyvol->lastWorkEndDate->toDateString() . ' (' . $cmtyvol->lastWorkEndDate->diffForHumans() . ')'
                    : null,
                'overview' => false,
                'section' => 'occupation',
            ],
            [
                'label_key' => 'cmtyvol.working_since_days',
                'icon' => null,
                'value' => fn ($cmtyvol) => $cmtyvol->workingSinceDays,
                'overview' => false,
                'section' => 'occupation',
            ],
            [
                'label_key' => 'people.notes',
                'icon' => null,
                'value' => fn ($cmtyvol) => $cmtyvol->notes,
                'value_html' => fn ($cmtyvol) => nl2br($cmtyvol->notes),
                'overview' => false,
                'section' => 'general',
                'import_labels' => ['Notes'],
                'assign' => function ($cmtyvol, $value) {
                    $cmtyvol->notes = $value;
                },
                'form_type' => 'textarea',
                'form_name' => 'notes',
            ],
            [
                'label_key' => 'app.comments',
                'value' => fn ($cmtyvol) => $cmtyvol->comments
                    ->sortBy('created_at')
                    ->pluck('content')
                    ->implode('; '),
                'overview' => false,
                'exclude_export' => false,
                'exclude_show' => true,
                'form_name' => 'comments',
            ],
        ];
    }

    protected function getWorkStatuses(): Collection
    {
        return collect([
            'active' => __('people.active'),
            'future' => __('people.future'),
            'alumni' => __('people.alumni'),
        ]);
    }

    protected function getGroupings()
    {
        return collect([
            'nationalities' => [
                'label' => __('people.nationalities'),
                'groups' => fn () => CommunityVolunteer::nationalities(true),
                'query' => fn ($query, $value) => $query->hasNationality($value),
            ],
            'languages' => [
                'label' => __('people.languages'),
                'groups' => fn () => CommunityVolunteer::languages(true),
                'query' => fn ($query, $value) => $query->speaksLanguage($value),
            ],
            'gender' => [
                'label' => __('people.gender'),
                'groups' => fn () => CommunityVolunteer::genders(true),
                'query' => fn ($query, $value) => $query->hasGender($value),
                'label_transform' => fn ($groups) => collect($groups)
                    ->map(function ($s) {
                        switch ($s) {
                            case 'f':
                                return __('app.female');
                            case 'm':
                                return __('app.male');
                            default:
                                return $s;
                        }
                    }),
            ],
            'responsibilities' => [
                'label' => __('app.responsibilities'),
                'groups' => fn () => Responsibility::has('communityVolunteers')
                    ->orderBy('name')
                    ->get()
                    ->pluck('name')
                    ->toArray(),
                'query' => fn ($q, $v) => $q->whereHas('responsibilities', fn (Builder $query) => $query->where('name', $v)),
            ],
            'pickup_locations' => [
                'label' => __('people.pickup_locations'),
                'groups' => fn () => CommunityVolunteer::pickupLocations(true),
                'query' => fn ($query, $value) => $query->withPickupLocation($value),
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

    protected function getColumnSets()
    {
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
            'name_nationality_comments' => [
                'label' => __('app.comments'),
                'columns' => ['name', 'family_name', 'nickname', 'nationality', 'comments'],
            ],
        ]);
    }

    protected function getSorters()
    {
        return collect([
            'first_name' => [
                'label' => __('app.first_name'),
                'sorting' => 'first_name',
            ],
            'last_name' => [
                'label' => __('app.last_name'),
                'sorting' => 'family_name',
            ],
            'nationality' => [
                'label' => __('people.nationality'),
                'sorting' => 'nationality',
            ],
            'gender' => [
                'label' => __('people.gender'),
                'sorting' => 'gender',
            ],
            'age' => [
                'label' => __('people.age'),
                'sorting' => 'age',
            ],
            'residence' => [
                'label' => __('people.residence'),
                'sorting' => 'residence',
            ],
            'pickup_location' => [
                'label' => __('people.pickup_location'),
                'sorting' => 'pickup_location',
            ],
        ]);
    }

    protected static function getAllTranslations($key)
    {
        return collect(language()->allowed())
            ->keys()
            ->map(fn ($lk) => __($key, [], $lk));
    }
}
