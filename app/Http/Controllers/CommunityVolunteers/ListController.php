<?php

namespace App\Http\Controllers\CommunityVolunteers;

use App\Models\CommunityVolunteers\CommunityVolunteer;
use App\Models\CommunityVolunteers\Responsibility;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class ListController extends BaseController
{
    public function index(Request $request): View
    {
        $this->authorize('viewAny', CommunityVolunteer::class);

        // Fields
        $fields = collect($this->getFields())
            ->where('overview', true)
            ->filter(fn ($field) => self::isFieldViewAuthorized($field));

        // Work status
        $workStatuses = $this->getWorkStatuses();
        if ($request->work_status != null && $workStatuses->keys()->contains($request->work_status)) {
            $workStatus = $request->work_status;
            $request->session()->put('cmtyvol_work_status', $workStatus);
        } else {
            $workStatus = $request->session()->get('cmtyvol_work_status', 'active');
        }

        // Groupings
        $groupings = $this->getGroupings()
            ->filter(fn ($e) => ! isset($e['authorized']) || $e['authorized']);
        if ($request->grouping != null && $groupings->keys()->contains($request->grouping)) {
            $grouping = $request->grouping;
            $request->session()->put('cmtyvol_grouping', $grouping);
        } elseif ($request->has('grouping') && $request->grouping == null) {
            $request->session()->forget('cmtyvol_grouping');
            $grouping = null;
        } else {
            $grouping = $request->session()->get('cmtyvol_grouping', null);
        }

        $data = collect();
        if ($grouping != null) {
            $groups = $groupings->get($grouping)['groups']();
            foreach ($groups as $value) {
                $q = CommunityVolunteer::workStatus($workStatus);
                $groupings->get($grouping)['query']($q, $value);
                $data->push(
                    $q->orderBy('first_name')
                        ->get()
                        ->mapWithKeys(fn ($cmtyvol) => [
                            $cmtyvol->id => [
                                'model' => $cmtyvol,
                                'fields' => $fields
                                    ->map(fn ($field) => self::overviewFieldMap($field, $cmtyvol))
                                    ->toArray(),
                            ],
                        ])
                );
            }
            if (isset($groupings->get($grouping)['label_transform']) && is_callable($groupings->get($grouping)['label_transform'])) {
                $groups = $groupings->get($grouping)['label_transform']($groups);
            }
        } else {
            $data = CommunityVolunteer::workStatus($workStatus)
                ->orderBy('first_name')
                ->get()
                ->mapWithKeys(fn ($cmtyvol) => [
                    $cmtyvol->id => [
                        'model' => $cmtyvol,
                        'fields' => $fields
                            ->map(fn ($field) => self::overviewFieldMap($field, $cmtyvol))
                            ->toArray(),
                    ],
                ]);
        }

        // Displays
        $displays = $this->getViews();
        if ($request->display != null && $displays->keys()->contains($request->display)) {
            $display = $request->display;
            $request->session()->put('cmtyvol_display', $display);
        } else {
            $display = $request->session()->get('cmtyvol_display', $displays->keys()->first());
        }

        return view('cmtyvol.index', [
            'fields' => $fields->map(fn ($f) => [
                'label' => $f['label'],
                'icon' => $f['icon'],
                'icon_style' => $f['icon_style'] ?? null,
            ]),
            'groups' => $groups ?? null,
            'data' => $data,
            'work_statuses' => $workStatuses->map(fn ($v, $k) => [
                'label' => $v,
                'url' => route('cmtyvol.index', ['work_status' => $k]),
                'active' => ($workStatus == $k),
            ]),
            'groupings' => $groupings->map(fn ($v, $k) => [
                'label' => $v['label'],
                'url' => route('cmtyvol.index', ['grouping' => $k]),
                'active' => ($grouping == $k),
            ]),
            'displays' => $displays->map(fn ($v, $k) => [
                'label' => $v['label'],
                'icon' => $v['icon'],
                'url' => route('cmtyvol.index', ['display' => $k]),
                'active' => ($display == $k),
            ]),
            'selected_display' => $display,
        ]);
    }

    private static function overviewFieldMap(array $field, CommunityVolunteer $cmtyvol): array
    {
        return [
            'detail_link' => isset($field['detail_link']) && $field['detail_link'],
            'value' => self::getFieldValue($field, $cmtyvol),
        ];
    }

    public function create(): View
    {
        $this->authorize('create', CommunityVolunteer::class);

        $fields = collect($this->getFields());

        return view('cmtyvol.create', [
            'fields' => $fields
                ->filter(fn ($field) => self::isRequiredField($field))
                ->filter(fn ($field) => self::isFieldChangeAuthorized($field))
                ->filter(fn ($field) => isset($field['form_name']) && isset($field['form_type']))
                ->map(fn ($f) => self::createFormField($f, null))
                ->toArray(),
        ]);
    }

    private static function createFormField($f, $value): array
    {
        // Calculate required attribute
        $required = false;
        if (isset($f['form_validate'])) {
            $rules = is_callable($f['form_validate']) ? $f['form_validate']() : $f['form_validate'];
            if (! is_array($rules)) {
                $rules = explode('|', $rules);
            }
            $required = in_array('required', $rules);
        }

        return [
            'label' => $f['label'],
            'name' => $f['form_name'],
            'type' => $f['form_type'],
            'prefix' => $f['prefix'] ?? null,
            'placeholder' => $f['form_placeholder'] ?? null,
            'help' => $f['form_help'] ?? null,
            'list' => $f['form_list'] ?? null,
            'required' => $required ? 'required' : null,
            'autocomplete' => isset($f['form_autocomplete']) && is_callable($f['form_autocomplete']) ? $f['form_autocomplete']() : null,
            'value' => $value,
        ];
    }

    public function store(Request $request): RedirectResponse
    {
        $this->authorize('create', CommunityVolunteer::class);

        $this->validateFormData($request);

        $cmtyvol = new CommunityVolunteer();
        $this->applyFormData($request, $cmtyvol);
        $cmtyvol->save();

        return redirect()
            ->route('cmtyvol.show', $cmtyvol)
            ->with('success', __('Community volunteer registered.'));
    }

    private static function isRequiredField($f): bool
    {
        return isset($f['form_validate']) && (
            (
                is_array($f['form_validate']) && in_array('required', $f['form_validate'])
            ) || (
                is_string($f['form_validate']) && in_array('required', explode('|', $f['form_validate']))
            )
        );
    }

    private function validateFormData($request): void
    {
        $request->validate(
            collect($this->getFields())
                ->filter(fn ($field) => self::isValidatableField($field))
                ->filter(fn ($field) => self::matchesSelectedSection($field, $request))
                ->mapWithKeys(fn ($field) => self::getFormRulesMap($field))
                ->toArray()
        );
    }

    private static function isValidatableField(array $field): bool
    {
        return isset($field['form_name']) && isset($field['form_type'])
            && isset($field['assign']) && is_callable($field['assign'])
            && isset($field['form_validate']);
    }

    private static function matchesSelectedSection(array $field, Request $request): bool
    {
        return ! $request->has('section') || $field['section'] == $request->section;
    }

    private static function getFormRulesMap(array $field): array
    {
        $rules = is_callable($field['form_validate']) ? $field['form_validate']() : $field['form_validate'];
        if ($field['form_type'] == 'checkboxes') {
            return [
                $field['form_name'] = 'array',
                $field['form_name'].'.*' => $rules,
            ];
        } elseif (isset($field['form_validate_extra'])) {
            $extra_rules = is_callable($field['form_validate_extra'])
                ? $field['form_validate_extra']()
                : $field['form_validate_extra'];

            return [
                $field['form_name'] => $rules,
            ] + $extra_rules;
        }

        return [
            $field['form_name'] => $rules,
        ];
    }

    private function applyFormData(Request $request, CommunityVolunteer $cmtyvol): void
    {
        collect($this->getFields())
            ->filter(fn ($field) => self::isFieldChangeAuthorized($field))
            ->filter(fn ($field) => self::isAssignableField($field))
            ->filter(fn ($field) => self::matchesSelectedSection($field, $request))
            ->each(function ($field) use ($cmtyvol, $request) {
                self::assignValueForField($request, $field, $cmtyvol);
            });
    }

    private static function isFieldViewAuthorized(array $field): bool
    {
        return ! isset($field['authorized_view']) || $field['authorized_view'];
    }

    private static function isFieldChangeAuthorized(array $field): bool
    {
        return ! isset($field['authorized_change']) || $field['authorized_change'];
    }

    private static function isAssignableField(array $field): bool
    {
        return isset($field['form_name']) && isset($field['form_type'])
            && isset($field['assign']) && is_callable($field['assign']);
    }

    private static function assignValueForField(Request $request, array $field, CommunityVolunteer &$cmtyvol)
    {
        if (isset($request->{$field['form_name'].'_delete'}) && isset($field['cleanup']) && is_callable($field['cleanup'])) {
            $field['cleanup']($cmtyvol);
        }
        $field['assign']($cmtyvol, $request->{$field['form_name']});
    }

    public function edit(CommunityVolunteer $cmtyvol): View
    {
        $this->authorize('update', $cmtyvol);

        $sections = $this->getSections();
        $fields = collect($this->getFields());
        $data = collect($sections)
            ->mapWithKeys(fn ($section, $section_key) => [
                $section_key => $fields->where('section', $section_key)
                    ->filter(fn ($field) => self::isFieldChangeAuthorized($field))
                    ->filter(fn ($field) => isset($field['form_name']) && isset($field['form_type']))
                    ->map(fn ($f) => self::createFormField($f, is_callable($f['value']) ? $f['value']($cmtyvol) : $cmtyvol->{$f['value']}))
                    ->toArray(),
            ]);

        return view('cmtyvol.edit', [
            'cmtyvol' => $cmtyvol,
            'sections' => $sections,
            'data' => $data,
        ]);
    }

    public function update(CommunityVolunteer $cmtyvol, Request $request): RedirectResponse
    {
        $this->authorize('update', $cmtyvol);

        $this->validateFormData($request);

        $this->applyFormData($request, $cmtyvol);
        $cmtyvol->save();

        return redirect()
            ->route('cmtyvol.show', $cmtyvol)
            ->with('success', __('Community volunteer updated.'));
    }

    public function destroy(CommunityVolunteer $cmtyvol): RedirectResponse
    {
        $this->authorize('delete', $cmtyvol);

        $cmtyvol->delete();

        return redirect()
            ->route('cmtyvol.index')
            ->with('success', __('Community volunteer deleted.'));
    }

    private static function getFieldValue(array $field, CommunityVolunteer $cmtyvol, bool $with_html = true): mixed
    {
        $value = null;
        if ($with_html && isset($field['value_html']) && is_callable($field['value_html'])) {
            $value = $field['value_html']($cmtyvol);
        } else {
            $value = is_callable($field['value']) ? $field['value']($cmtyvol) : $cmtyvol->{$field['value']};
            if ($value != null) {
                $value = htmlspecialchars($value);
            }
        }
        if ($value != null) {
            $value = ($field['prefix'] ?? '').$value;
        }

        return $value;
    }

    public function responsibilities(CommunityVolunteer $cmtyvol): View
    {
        $responsibilities = Responsibility::where('available', true)
            ->orderBy('name')
            ->get()
            ->map(function ($responsibility) {
                return [
                    'text' => $responsibility->name,
                    'description' => $responsibility->description,
                    'hidden' => $responsibility->isCapacityExhausted,
                ];
            });

        return view('cmtyvol.responsibilities', [
            'cmtyvol' => $cmtyvol,
            'responsibilities' => $responsibilities,
            'value' => $cmtyvol->responsibilities
                ->map(fn (Responsibility $r) => [
                    'value' => $r->name,
                    'from' => $r->getRelationValue('pivot')->start_date,
                    'to' => $r->getRelationValue('pivot')->end_date,
                ]),
        ]);
    }

    public function updateResponsibilities(CommunityVolunteer $cmtyvol, Request $request): RedirectResponse
    {
        $request->validate([
            'responsibilities' => 'array',
            'responsibilities.*.name' => [
                Rule::in(
                    Responsibility::select('name')
                        ->pluck('name')
                        ->all()
                ),
                'required_with:responsibilities.*.from,responsibilities.*.to',
            ],
            'responsibilities.*.from' => [
                'required_with:responsibilities.*.to',
                'nullable',
                'date',

            ],
            'responsibilities.*.to' => [
                'nullable',
                'date',
                'after_or_equal:responsibilities.*.from',
            ],
        ]);

        $value = $request->input('responsibilities');
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

        return redirect()
            ->route('cmtyvol.show', $cmtyvol)
            ->with('success', __('Community volunteer updated.'));
    }
}
