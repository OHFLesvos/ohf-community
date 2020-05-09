<?php

namespace App\Http\Controllers\Helpers;

use App\Models\Helpers\Helper;
use App\Models\People\Person;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Validation\Rule;

class HelperListController extends BaseHelperController
{
    public function index(Request $request)
    {
        $this->authorize('viewAny', Helper::class);

        // Fields
        $fields = collect($this->getFields())
            ->where('overview', true)
            ->filter(fn ($field) => self::isFieldViewAuthorized($field));

        // Scope
        $scopes = $this->getScopes();
        if ($request->scope != null && $scopes->keys()->contains($request->scope)) {
            $scope = $request->scope;
            $request->session()->put('helpers_scope', $scope);
        } else {
            $scope = $request->session()->get('helpers_scope', $scopes->keys()->first());
        }
        $scope_method = $scopes->get($scope)['scope'];

        // Groupings
        $groupings = $this->getGroupings()
            ->filter(fn ($e) => ! isset($e['authorized']) || $e['authorized']);
        if ($request->grouping != null && $groupings->keys()->contains($request->grouping)) {
            $grouping = $request->grouping;
            $request->session()->put('helpers_grouping', $grouping);
        } elseif ($request->has('grouping') && $request->grouping == null) {
            $request->session()->forget('helpers_grouping');
            $grouping = null;
        } else {
            $grouping = $request->session()->get('helpers_grouping', null);
        }
        $data = collect();
        if ($grouping != null) {
            $groups = $groupings->get($grouping)['groups']();
            foreach ($groups as $value) {
                $q = Helper::$scope_method();
                $groupings->get($grouping)['query']($q, $value);
                $data->push($q
                    ->get()
                    ->load('person')
                    ->sortBy('person.name')
                    ->mapWithKeys(fn ($helper) => [
                        $helper->id => [
                            'model' => $helper,
                            'fields' => $fields
                                ->map(fn ($field) => self::overviewFieldMap($field, $helper))
                                ->toArray(),
                        ],
                    ])
                );
            }
            if (isset($groupings->get($grouping)['label_transform']) && is_callable($groupings->get($grouping)['label_transform'])) {
                $groups = $groupings->get($grouping)['label_transform']($groups);
            }
        } else {
            $data = Helper::$scope_method()
                ->get()
                ->load('person')
                ->sortBy('person.name')
                ->mapWithKeys(fn ($helper) => [
                    $helper->id => [
                        'model' => $helper,
                        'fields' => $fields
                            ->map(fn ($field) => self::overviewFieldMap($field, $helper))
                            ->toArray(),
                    ],
                ]);
        }

        // Displays
        $displays = $this->getViews();
        if ($request->display != null && $displays->keys()->contains($request->display)) {
            $display = $request->display;
            $request->session()->put('helpers_display', $display);
        } else {
            $display = $request->session()->get('helpers_display', $displays->keys()->first());
        }

        return view('helpers.index', [
            'fields' => $fields->map(fn ($f) => [
                'label' => __($f['label_key']),
                'icon' => $f['icon'],
            ]),
            'groups' => $groups ?? null,
            'data' => $data,
            'scopes' => $scopes->map(fn ($v, $k) => [
                'label' => $v['label'],
                'url' => route('people.helpers.index', ['scope' => $k]),
                'active' => ($scope == $k),
            ]),
            'groupings' => $groupings->map(fn ($v, $k) => [
                'label' => $v['label'],
                'url' => route('people.helpers.index', ['grouping' => $k]),
                'active' => ($grouping == $k),
            ]),
            'displays' => $displays->map(fn ($v, $k) => [
                'label' => $v['label'],
                'icon' => $v['icon'],
                'url' => route('people.helpers.index', ['display' => $k]),
                'active' => ($display == $k),
            ]),
            'selected_display' => $display,
        ]);
    }

    private static function overviewFieldMap(array $field, Helper $helper): array
    {
        return [
            'detail_link' => isset($field['detail_link']) && $field['detail_link'],
            'value' => self::getFieldValue($field, $helper),
        ];
    }

    public function createFrom()
    {
        $this->authorize('create', Helper::class);

        return view('helpers.createFrom');
    }

    public function storeFrom(Request $request)
    {
        $this->authorize('create', Helper::class);

        $request->validate([
            'person_id' => [
                'required',
                Rule::exists('persons', 'public_id'),
                function ($attribute, $value, $fail) {
                    if (Person::where('public_id', $value)->has('helper')->first() != null) {
                        return $fail(__('people.helper_already_exists'));
                    }
                },
            ],
        ]);

        $person = Person::where('public_id', $request->person_id)->firstOrFail();
        $helper = new Helper();
        $person->helper()->save($helper);
        return redirect()
            ->route('people.helpers.show', $helper)
            ->with('success', __('people.helper_registered'));
    }

    public function create()
    {
        $this->authorize('create', Helper::class);

        $fields = collect($this->getFields());

        return view('helpers.create', [
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
        $this->authorize('create', Helper::class);

        $this->validateFormData($request);

        $person = new Person();
        $helper = new Helper();

        $this->applyFormData($request, $person, $helper);

        $person->save();
        $person->helper()->save($helper);

        return redirect()
            ->route('people.helpers.show', $helper)
            ->with('success', __('people.helper_registered'));
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
        }
        return [
            $field['form_name'] => $rules,
        ];
    }

    private function applyFormData(Request $request, Person $person, Helper $helper)
    {
        collect($this->getFields())
            ->filter(fn ($field) => self::isFieldChangeAuthorized($field))
            ->filter(fn ($field) => self::isAssignableField($field))
            ->filter(fn ($field) => self::matchesSelectedSection($field, $request))
            ->each(function ($field) use ($person, $helper, $request) {
                self::assignValueForField($request, $field, $person, $helper);
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

    private static function assignValueForField(Request $request, array $field, Person &$person, Helper &$helper)
    {
        if (isset($request->{$field['form_name'].'_delete'}) && isset($field['cleanup']) && is_callable($field['cleanup'])) {
            $field['cleanup']($person, $helper);
        }
        $field['assign']($person, $helper, $request->{$field['form_name']});
    }

    public function show(Helper $helper)
    {
        $this->authorize('view', $helper);

        $sections = $this->getSections();
        $fields = collect($this->getFields());

        return view('helpers.show', [
            'helper' => $helper,
            'sections' => $sections,
            'data' => collect(array_keys($sections))
                ->filter(fn ($section) => self::getFieldsForSection($fields, $section)->count() > 0)
                ->mapWithKeys(fn ($section) => [
                    $section => self::getFieldsForSection($fields, $section)
                        ->map(fn ($f) => [
                            'label' => __($f['label_key']),
                            'icon' => $f['icon'],
                            'value' => self::getFieldValue($f, $helper),
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

    public function edit(Helper $helper, Request $request)
    {
        $this->authorize('update', $helper);

        $sections = $this->getSections();
        $fields = collect($this->getFields());

        $request->validate([
            'section' => [
                'nullable',
                Rule::in(array_keys($sections)),
            ],
        ]);
        if ($request->has('section')) {
            return view('helpers.edit_section', [
                'helper' => $helper,
                'fields' => $fields->where('section', $request->section)
                    ->filter(fn ($field) => self::isFieldChangeAuthorized($field))
                    ->filter(fn ($field) => isset($field['form_name']) && isset($field['form_type']))
                    ->map(fn ($f) => self::createFormField($f, is_callable($f['value']) ? $f['value']($helper) : $helper->{$f['value']}))
                    ->toArray(),
                'section' => $request->section,
                'section_label' => $sections[$request->section],
            ]);
        }
        return view('helpers.edit', [
            'helper' => $helper,
            'data' => collect(array_keys($sections))
                ->mapWithKeys(fn ($section) => [
                    $sections[$section] => $fields
                        ->where('section', $section)
                        ->filter(fn ($field) => self::isFieldChangeAuthorized($field))
                        ->filter(fn ($field) => isset($field['form_name']) && isset($field['form_type']))
                        ->map(fn ($f) => self::createFormField($f, is_callable($f['value']) ? $f['value']($helper) : $helper->{$f['value']}))
                        ->toArray(),
                ])
                ->toArray(),
        ]);
    }

    public function update(Helper $helper, Request $request)
    {
        $this->authorize('update', $helper);

        $sections = $this->getSections();
        $request->validate([
            'section' => [
                'nullable',
                Rule::in(array_keys($sections)),
            ],
        ]);

        $this->validateFormData($request);

        $this->applyFormData($request, $helper->person, $helper);

        $helper->save();
        $helper->person->save();

        return redirect()
            ->route('people.helpers.show', $helper)
            ->with('success', __('people.helper_updated'));
    }

    public function destroy(Helper $helper)
    {
        $this->authorize('delete', $helper);

        $helper->delete();

        return redirect()
            ->route('people.helpers.index')
            ->with('success', __('people.helper_deleted'));
    }

    private static function getFieldValue($field, $helper, $with_html = true)
    {
        $value = null;
        if ($with_html && isset($field['value_html']) && is_callable($field['value_html'])) {
            $value = $field['value_html']($helper);
        } else {
            $value = is_callable($field['value']) ? $field['value']($helper) : $helper->{$field['value']};
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
