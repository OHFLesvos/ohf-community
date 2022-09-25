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
    protected function getSections(): array
    {
        return [
            'portrait' => __('Portrait'),
            'general' => __('General'),
            'reachability' => __('Reachability'),
            'occupation' => __('Occupation'),
        ];
    }

    protected function getFields(): array
    {
        return [
            [
                'label' => __('Portrait Picture'),
                'icon' => null,
                'value' => fn (CommunityVolunteer $cmtyvol) => $cmtyvol->portrait_picture,
                'value_html' => fn (CommunityVolunteer $cmtyvol) => isset($cmtyvol->portrait_picture) ? '<img src="'.Storage::url($cmtyvol->portrait_picture).'" class="img-fluid img-thumbnail">' : null,
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
                'form_help' => __('Image will be cropped/resized to 2:3 aspect ratio if necessary.'),
            ],
            [
                'label' => __('First Name'),
                'icon' => null,
                'value' => fn (CommunityVolunteer $cmtyvol) => $cmtyvol->first_name,
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
                'label' => __('Family Name'),
                'icon' => null,
                'value' => fn (CommunityVolunteer $cmtyvol) => $cmtyvol->family_name,
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
                'label' => __('Nickname'),
                'icon' => null,
                'value' => fn (CommunityVolunteer $cmtyvol) => $cmtyvol->nickname,
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
                'label' => __('Nationality'),
                'icon' => 'globe',
                'value' => fn (CommunityVolunteer $cmtyvol) => $cmtyvol->nationality,
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
                'label' => __('Gender'),
                'icon' => null,
                'value' => fn (CommunityVolunteer $cmtyvol) => $cmtyvol->gender != null ? ($cmtyvol->gender == 'f' ? __('Female') : __('Male')) : null,
                'value_html' => fn (CommunityVolunteer $cmtyvol) => $cmtyvol->gender != null
                    ? view('components.icon-gender', [
                        'gender' => $cmtyvol->gender,
                        'attributes' => new ComponentAttributeBag(),
                    ]) : null,
                'overview' => true,
                'section' => 'general',
                'assign' => function ($cmtyvol, $value) {
                    if (collect([__('Female'), 'f', 'w', 'Frau', 'Fräulein', 'Fr.', 'Fr', 'Frl.', 'Frl', 'Missus', 'Missis', 'Miss', 'Mrs.', 'Mrs', 'Ms.', 'Ms'])
                        ->map(fn ($t) => strtolower($t))
                        ->contains(strtolower($value))) {
                        $cmtyvol->gender = 'f';
                    } elseif (collect([__('Male'), 'm', 'Herr', 'Hr.', 'Hr', 'Mister', 'Mr.', 'Mr'])
                        ->map(fn ($t) => strtolower($t))
                        ->contains(strtolower($value))) {
                        $cmtyvol->gender = 'm';
                    } else {
                        $cmtyvol->gender = null;
                    }
                },
                'form_type' => 'radio',
                'form_name' => 'sex',
                'form_list' => [
                    null => __('Unspecified'),
                    __('Male') => __('Male'),
                    __('Female') => __('Female'),
                ],
                'form_validate' => 'required', // TODO better validation |in:m,f
            ],
            [
                'label' => __('Date of birth'),
                'icon' => null,
                'value' => fn (CommunityVolunteer $cmtyvol) => $cmtyvol->date_of_birth,
                'overview' => false,
                'section' => 'general',
                'import_labels' => ['DOB'],
                'assign' => function ($cmtyvol, $value) {
                    $cmtyvol->date_of_birth = ! empty($value) ? Carbon::parse($value) : null;
                },
                'form_type' => 'text',
                'form_name' => 'date_of_birth',
                'form_placeholder' => 'YYYY-MM-DD',
                'form_validate' => 'required|date',
            ],
            [
                'label' => __('Age'),
                'icon' => null,
                'value' => fn (CommunityVolunteer $cmtyvol) => $cmtyvol->age,
                'overview' => true,
                'section' => 'general',
            ],
            [
                'label' => __('Police Number'),
                'icon' => 'id-card',
                'value' => fn (CommunityVolunteer $cmtyvol) => $cmtyvol->police_no,
                'overview' => false,
                'section' => 'general',
                'import_labels' => ['Police No.'],
                'assign' => function ($cmtyvol, $value) {
                    $cmtyvol->police_no = (! empty($value) ? $value : null);
                },
                'form_type' => 'text',
                'form_name' => 'police_number',
                'form_validate' => 'nullable',
            ],
            [
                'label' => __('Languages'),
                'icon' => 'language',
                'value' => fn (CommunityVolunteer $cmtyvol) => $cmtyvol->languages != null ? (is_array($cmtyvol->languages) ? implode(', ', $cmtyvol->languages) : $cmtyvol->languages) : null,
                'value_html' => fn (CommunityVolunteer $cmtyvol) => $cmtyvol->languages != null ? (is_array($cmtyvol->languages) ? implode('<br>', $cmtyvol->languages) : $cmtyvol->languages) : null,
                'overview' => false,
                'section' => 'general',
                'assign' => function ($cmtyvol, $value) {
                    $cmtyvol->languages = ($value != null ? array_unique(array_map('trim', preg_split('/(\s*[,;\/|]\s*)|(\s+and\s+)/', $value))) : null);
                },
                'form_type' => 'text',
                'form_name' => 'languages',
                'form_help' => __('Separate by comma'),
                'form_autocomplete' => fn () => CommunityVolunteer::languages(),
            ],
            [
                'label' => __('Local Phone'),
                'icon' => 'phone',
                'value' => 'local_phone',
                'value_html' => fn (CommunityVolunteer $cmtyvol) => $cmtyvol->local_phone != null
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
                'label' => __('Other Phone'),
                'icon' => 'phone',
                'value' => 'other_phone',
                'value_html' => fn (CommunityVolunteer $cmtyvol) => $cmtyvol->other_phone != null
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
                'label' => __('WhatsApp'),
                'icon' => 'whatsapp',
                'icon_style' => 'brands',
                'value' => 'whatsapp',
                'value_html' => fn (CommunityVolunteer $cmtyvol) => $cmtyvol->whatsapp != null
                    ? view('components.links.whatsapp', [
                        'slot' => $cmtyvol->whatsapp,
                        'message' => 'Hello '.$cmtyvol->first_name."\n",
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
                'label' => __('Email address'),
                'icon' => 'envelope',
                'value' => 'email',
                'value_html' => fn (CommunityVolunteer $cmtyvol) => $cmtyvol->email != null
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
                'label' => __('Skype'),
                'icon' => 'skype',
                'icon_style' => 'brands',
                'value' => 'skype',
                'value_html' => fn (CommunityVolunteer $cmtyvol) => $cmtyvol->skype != null
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
                'label' => __('Residence'),
                'icon' => null,
                'value' => 'residence',
                'value_html' => fn (CommunityVolunteer $cmtyvol) => nl2br($cmtyvol->residence),
                'overview' => false,
                'section' => 'reachability',
                'assign' => function ($cmtyvol, $value) {
                    $cmtyvol->residence = $value;
                },
                'form_type' => 'textarea',
                'form_name' => 'residence',
            ],
            [
                'label' => __('Pickup location'),
                'icon' => null,
                'value' => 'pickup_location',
                'value_html' => fn (CommunityVolunteer $cmtyvol) => nl2br($cmtyvol->pickup_location),
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
                'label' => __('Responsibilities'),
                'icon' => null,
                'value' => fn (CommunityVolunteer $cmtyvol) => $cmtyvol->responsibilities
                    ->map(fn (Responsibility $r) => [
                        'value' => $r->name,
                        'from' => $r->getRelationValue('pivot')->start_date,
                        'to' => $r->getRelationValue('pivot')->end_date,
                    ]),
                'value_export' => fn (CommunityVolunteer $cmtyvol) => $cmtyvol->responsibilities()
                    ->orderBy('start_date')
                    ->get()
                    ->map(fn (Responsibility $r) => [
                        'value' => $r->name,
                        'from' => $r->getRelationValue('pivot')->start_date,
                        'to' => $r->getRelationValue('pivot')->end_date,
                    ])
                    ->pluck('value')
                    ->implode('; '),
                'value_html' => fn (CommunityVolunteer $cmtyvol) => $cmtyvol->responsibilities
                    ->map(function (Responsibility $r) {
                        $str = htmlspecialchars($r->name);
                        if ($r->description !== null) {
                            $str .= ' <a tabindex="0" class="description-tooltip fa-solid fa-info-circle" data-toggle="popover" data-trigger="focus" data-content="'.htmlspecialchars($r->description).'"></a>';
                        }
                        if ($r->getRelationValue('pivot')->hasDateRange()) {
                            $str .= ' ('.$r->getRelationValue('pivot')->date_range_string.')';
                        }
                        if ($r->hasAssignedAlthoughNotAvailable) {
                            $str .= ' <span class="text-danger">('.__('not available').')</span>';
                        }
                        if ($r->isCapacityExhausted) {
                            $str .= ' <span class="text-danger">('.__('capacity exhausted').')</span>';
                        }

                        return $str;
                    })
                    ->implode('<br>'),
                'overview' => true,
                'section' => 'occupation',
                'import_labels' => ['Project'],
                'assign' => function (CommunityVolunteer $cmtyvol, $value) {
                    DB::transaction(function () use ($cmtyvol, $value) {
                        $cmtyvol->responsibilities()->detach();
                        if ($value != null) {
                            if (! is_array($value)) {
                                $values = [];
                                foreach (preg_split('/(\s*[,\/|]\s*)|(\s+and\s+)/', $value) as $v) {
                                    $values[] = $v;
                                }
                                $value = array_map('trim', $values);
                            }

                            collect($value)->map(function ($entry) use ($cmtyvol) {
                                if (! is_array($entry)) {
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
                'label' => __('Starting Date'),
                'icon' => null,
                'value' => fn (CommunityVolunteer $cmtyvol) => optional($cmtyvol->first_work_start_date)->toDateString(),
                'value_html' => fn (CommunityVolunteer $cmtyvol) => $cmtyvol->first_work_start_date != null
                    ? $cmtyvol->first_work_start_date->toDateString().' ('.$cmtyvol->first_work_start_date->diffForHumans().')'
                    : null,
                'overview' => false,
                'section' => 'occupation',
            ],
            [
                'label' => __('Leaving Date'),
                'icon' => null,
                'value' => fn (CommunityVolunteer $cmtyvol) => optional($cmtyvol->last_work_end_date)->toDateString(),
                'value_html' => fn (CommunityVolunteer $cmtyvol) => $cmtyvol->last_work_end_date != null
                    ? $cmtyvol->last_work_end_date->toDateString().' ('.$cmtyvol->last_work_end_date->diffForHumans().')'
                    : null,
                'overview' => false,
                'section' => 'occupation',
            ],
            [
                'label' => __('Working since (days)'),
                'icon' => null,
                'value' => fn (CommunityVolunteer $cmtyvol) => $cmtyvol->working_since_days,
                'overview' => false,
                'section' => 'occupation',
            ],
            [
                'label' => __('Notes'),
                'icon' => null,
                'value' => fn (CommunityVolunteer $cmtyvol) => $cmtyvol->notes,
                'value_html' => fn (CommunityVolunteer $cmtyvol) => nl2br($cmtyvol->notes),
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
                'label' => __('Comments'),
                'value' => fn (CommunityVolunteer $cmtyvol) => $cmtyvol->comments
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
            'active' => __('Active'),
            'future' => __('Future'),
            'alumni' => __('Alumni'),
        ]);
    }

    protected function getGroupings(): Collection
    {
        return collect([
            'nationalities' => [
                'label' => __('Nationalities'),
                'groups' => fn () => CommunityVolunteer::nationalities(true),
                'query' => fn (Builder $query, $value) => $query->where('nationality', $value),
            ],
            'languages' => [
                'label' => __('Languages'),
                'groups' => fn () => CommunityVolunteer::languages(true),
                'query' => fn (Builder $query, $value) => $query->where('languages', 'like', '%"'.$value.'"%'),
            ],
            'gender' => [
                'label' => __('Gender'),
                'groups' => fn () => CommunityVolunteer::genders(true),
                'query' => fn (Builder $query, $value) => $query->where('gender', $value),
                'label_transform' => fn ($groups) => collect($groups)
                    ->map(function ($s) {
                        switch ($s) {
                            case 'f':
                                return __('Female');
                            case 'm':
                                return __('Male');
                            default:
                                return $s;
                        }
                    }),
            ],
            'responsibilities' => [
                'label' => __('Responsibilities'),
                'groups' => fn () => Responsibility::has('communityVolunteers')
                    ->orderBy('name')
                    ->pluck('name')
                    ->toArray(),
                'query' => fn ($q, $v) => $q->whereHas('responsibilities', fn (Builder $query) => $query->where('name', $v)),
            ],
            'pickup_locations' => [
                'label' => __('Pickup locations'),
                'groups' => fn () => CommunityVolunteer::pickupLocations(true),
                'query' => fn (Builder $query, $value) => $query->where('pickup_location', $value),
            ],
        ]);
    }

    protected function getViews(): Collection
    {
        return collect([
            'list' => [
                'label' => __('List'),
                'icon' => 'list',
            ],
            'grid' => [
                'label' => __('Grid'),
                'icon' => 'table-cells-large',
            ],
        ]);
    }

    protected function getColumnSets(): Collection
    {
        return collect([
            'all' => [
                'label' => __('All'),
                'columns' => [],
            ],
            'name_nationality_occupation' => [
                'label' => __('Nationality, Occupation'),
                'columns' => ['name', 'family_name', 'nickname', 'nationality', 'responsibilities'],
            ],
            'contact_info' => [
                'label' => __('Contact information'),
                'columns' => ['name', 'family_name', 'nickname', 'local_phone', 'other_phone', 'whatsapp', 'email', 'skype', 'residence'],
            ],
            'name_nationality_comments' => [
                'label' => __('Comments'),
                'columns' => ['name', 'family_name', 'nickname', 'nationality', 'comments'],
            ],
        ]);
    }

    protected function getSorters(): Collection
    {
        return collect([
            'first_name' => [
                'label' => __('First Name'),
                'sorting' => 'first_name',
            ],
            'last_name' => [
                'label' => __('Last Name'),
                'sorting' => 'family_name',
            ],
            'nationality' => [
                'label' => __('Nationality'),
                'sorting' => 'nationality',
            ],
            'gender' => [
                'label' => __('Gender'),
                'sorting' => 'gender',
            ],
            'age' => [
                'label' => __('Age'),
                'sorting' => 'age',
            ],
            'residence' => [
                'label' => __('Residence'),
                'sorting' => 'residence',
            ],
            'pickup_location' => [
                'label' => __('Pickup location'),
                'sorting' => 'pickup_location',
            ],
        ]);
    }
}
