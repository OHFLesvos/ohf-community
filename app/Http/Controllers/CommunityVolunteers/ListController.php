<?php

namespace App\Http\Controllers\CommunityVolunteers;

use App\Models\CommunityVolunteers\CommunityVolunteer;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Validation\Rule;

class ListController extends BaseController
{
    public function index(Request $request)
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
                $data->push($q->orderBy('first_name')
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
                'label' => __($f['label_key']),
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

    public function create()
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

    private static function createFormField($f, $value)
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
            'label' => __($f['label_key']),
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

    public function store(Request $request)
    {
        $this->authorize('create', CommunityVolunteer::class);

        $this->validateFormData($request);

        $cmtyvol = new CommunityVolunteer();
        $this->applyFormData($request, $cmtyvol);
        $cmtyvol->save();

        return redirect()
            ->route('cmtyvol.show', $cmtyvol)
            ->with('success', __('cmtyvol.registered'));
    }

    private static function isRequiredField($f)
    {
        return isset($f['form_validate']) && (
            (
                is_array($f['form_validate']) && in_array('required', $f['form_validate'])
            ) || (
                is_string($f['form_validate']) && in_array('required', explode('|', $f['form_validate']))
            )
        );
    }

    private function validateFormData($request)
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
                $field['form_name'] . '.*' => $rules,
            ];
        } else if (isset($field['form_validate_extra'])) {
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

    private function applyFormData(Request $request, CommunityVolunteer $cmtyvol)
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

    public function show(CommunityVolunteer $cmtyvol)
    {
        $this->authorize('view', $cmtyvol);

        $sections = $this->getSections();
        $fields = collect($this->getFields());

        return view('cmtyvol.show', [
            'cmtyvol' => $cmtyvol,
            'sections' => $sections,
            'data' => collect(array_keys($sections))
                ->filter(fn ($section) => self::getFieldsForSection($fields, $section)->count() > 0)
                ->mapWithKeys(fn ($section) => [
                    $section => self::getFieldsForSection($fields, $section)
                        ->map(fn ($f) => [
                            'label' => __($f['label_key']),
                            'icon' => $f['icon'],
                            'icon_style' => $f['icon_style'] ?? null,
                            'value' => self::getFieldValue($f, $cmtyvol),
                        ])
                        ->whereNotIn('value', [null])
                        ->toArray(),
                ])
                ->toArray(),
        ]);
    }

    private static function getFieldsForSection(Collection $fields, string $section): Collection
    {
        return $fields->where('section', $section)
            ->where('overview_only', false)
            ->where('exclude_show', false)
            ->filter(fn ($field) => self::isFieldViewAuthorized($field));
    }

    public function edit(CommunityVolunteer $cmtyvol, Request $request)
    {
        $this->authorize('update', $cmtyvol);

        $sections = $this->getSections();
        $fields = collect($this->getFields());

        $request->validate([
            'section' => [
                'required',
                Rule::in(array_keys($sections)),
            ],
        ]);
        return view('cmtyvol.edit_section', [
            'cmtyvol' => $cmtyvol,
            'fields' => $fields->where('section', $request->section)
                ->filter(fn ($field) => self::isFieldChangeAuthorized($field))
                ->filter(fn ($field) => isset($field['form_name']) && isset($field['form_type']))
                ->map(fn ($f) => self::createFormField($f, is_callable($f['value']) ? $f['value']($cmtyvol) : $cmtyvol->{$f['value']}))
                ->toArray(),
            'section' => $request->section,
            'section_label' => $sections[$request->section],
        ]);
    }

    public function update(CommunityVolunteer $cmtyvol, Request $request)
    {
        $this->authorize('update', $cmtyvol);

        $sections = $this->getSections();
        $request->validate([
            'section' => [
                'required',
                Rule::in(array_keys($sections)),
            ],
        ]);

        $this->validateFormData($request);

        $this->applyFormData($request, $cmtyvol);
        $cmtyvol->save();

        return redirect()
            ->route('cmtyvol.show', $cmtyvol)
            ->with('success', __('cmtyvol.updated'));
    }

    public function destroy(CommunityVolunteer $cmtyvol)
    {
        $this->authorize('delete', $cmtyvol);

        $cmtyvol->delete();

        return redirect()
            ->route('cmtyvol.index')
            ->with('success', __('cmtyvol.deleted'));
    }

    private static function getFieldValue($field, $cmtyvol, $with_html = true)
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
            $value = ($field['prefix'] ?? '') . $value;
        }
        return $value;
    }
}
