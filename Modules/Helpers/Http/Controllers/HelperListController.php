<?php

namespace Modules\Helpers\Http\Controllers;

use Modules\People\Entities\Person;

use Modules\Helpers\Entities\Helper;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class HelperListController extends BaseHelperController
{
    public function index(Request $request) {
        $this->authorize('list', Helper::class);

        // Fields
        $fields = collect($this->getFields())
            ->where('overview', true)
            ->filter(function($e){ return !isset($e['authorized_view']) || $e['authorized_view']; });

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
        $groupings = $this->getGroupings()->filter(function($e){ return !isset($e['authorized']) || $e['authorized']; });
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
            foreach($groups as $value) {
                $q = Helper::$scope_method();
                $groupings->get($grouping)['query']($q, $value);
                $data->push($q
                    ->get()
                    ->load('person')
                    ->sortBy('person.name')
                    ->mapWithKeys(function($helper) use($fields) {
                        return [ $helper->id => [
                            'model' => $helper,
                            'fields' => $fields
                                ->map(function($field) use($helper){
                                    return [
                                        'detail_link' => isset($field['detail_link']) && $field['detail_link'],
                                        'value' => self::getFieldValue($field, $helper),
                                    ];
                                })
                                ->toArray()]
                            ];
                    })
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
                ->mapWithKeys(function($helper) use($fields) {
                    return [ $helper->id => [
                        'model' => $helper,
                        'fields' => $fields
                            ->map(function($field) use($helper){
                                return [
                                    'detail_link' => isset($field['detail_link']) && $field['detail_link'],
                                    'value' => self::getFieldValue($field, $helper),
                                ];
                            })
                            ->toArray()
                        ]
                    ];
                });
        }

        // Displays
        $displays = $this->getViews();
        if ($request->display != null && $displays->keys()->contains($request->display)) {
            $display = $request->display;
            $request->session()->put('helpers_display', $display);
        } else {
            $display = $request->session()->get('helpers_display', $displays->keys()->first());
        }

        return view('helpers::index', [
            'fields' => $fields->map(function($f){
                return [
                    'label' => __($f['label_key']),
                    'icon' => $f['icon'],
                ];
            }),
            'groups' => $groups ?? null,
            'data' => $data,
            'scopes' => $scopes->map(function($v, $k) use($scope) {
                return [
                    'label' => $v['label'],
                    'url' => route('people.helpers.index', ['scope' => $k]),
                    'active' => ($scope == $k),
                ];
            }),
            'groupings' => $groupings->map(function($v, $k) use($grouping) {
                return [
                    'label' => $v['label'],
                    'url' => route('people.helpers.index', ['grouping' => $k]),
                    'active' => ($grouping == $k),
                ];
            }),
            'displays' => $displays->map(function($v, $k) use($display) {
                return [
                    'label' => $v['label'],
                    'icon' => $v['icon'],
                    'url' => route('people.helpers.index', ['display' => $k]),
                    'active' => ($display == $k),
                ];
            }),
            'selected_display' => $display,
        ]);
    }

    public function createFrom(Request $request) {
        $this->authorize('create', Helper::class);

        return view('helpers::createFrom');
    }

    public function storeFrom(Request $request) {
        $this->authorize('create', Helper::class);

        $request->validate([
            'person_id' => [
                'required',
                Rule::exists('persons', 'public_id'),
                function($attribute, $value, $fail) {
                    if (Person::where('public_id', $value)->has('helper')->first() != null) {
                        return $fail(__('people::people.helper_already_exists'));
                    }
                },
            ],
        ]);

        $person = Person::where('public_id', $request->person_id)->firstOrFail();
        $helper = new Helper();
        $person->helper()->save($helper);
        return redirect()->route('people.helpers.edit', $helper)
            ->with('success', __('people::people.helper_registered'));
    }

    public function create(Request $request) {
        $this->authorize('create', Helper::class);

        $sections = $this->getSections();
        $fields = collect($this->getFields());

        return view('helpers::create', [
            'data' => collect(array_keys($sections))
                ->mapWithKeys(function($section) use($fields, $sections) {
                    return [ 
                        $sections[$section] => $fields
                            ->where('section', $section)
                            ->filter(function($e){ return !isset($e['authorized_change']) || $e['authorized_change']; })
                            ->filter(function($f){ return isset($f['form_name']) && isset($f['form_type']); })
                            ->map(function($f) {
                                $value = null;
                                return self::createFormField($f, $value);
                            })
                            ->toArray()
                    ];
                })
                ->toArray(),
        ]);
    }

    private static function createFormField($f, $value) {

        // Calculate required attribute
        $required = false;
        if (isset($f['form_validate'])) {
            $rules = is_callable($f['form_validate']) ? $f['form_validate']() : $f['form_validate'];
            if (!is_array($rules)) {
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

    public function store(Request $request) {
        $this->authorize('create', Helper::class);

        $this->validateFormData($request);

        $person = new Person();
        $helper = new Helper();

        $this->applyFormData($request, $person, $helper);

        $person->save();
        $person->helper()->save($helper);

        return redirect()->route('people.helpers.show', $helper)
            ->with('success', __('people::people.helper_registered'));	
    }

    private function validateFormData($request) {
        $request->validate(
            collect($this->getFields())
                ->filter(function($f){ 
                    return isset($f['form_name']) && isset($f['form_type']) 
                        && isset($f['assign']) && is_callable($f['assign'])
                        && isset($f['form_validate']);
                })
                ->mapWithKeys(function($f){
                    $rules = is_callable($f['form_validate']) ? $f['form_validate']() : $f['form_validate'];
                    if ($f['form_type'] == 'checkboxes') {
                        return [
                            $f['form_name'] = 'array',
                            $f['form_name'].'.*' => $rules,
                        ];
                    }
                    return [$f['form_name'] => $rules];
                })
                ->toArray()
        );
    }

    private function applyFormData($request, $person, $helper) {
        collect($this->getFields())
            ->filter(function($e){ return !isset($e['authorized_change']) || $e['authorized_change']; })
            ->filter(function($f){ 
                return isset($f['form_name']) && isset($f['form_type']) 
                    && isset($f['assign']) && is_callable($f['assign']);
            })
            ->each(function($f) use($person, $helper, $request) {
                if (isset($request->{$f['form_name'].'_delete'}) && isset($f['cleanup']) && is_callable($f['cleanup'])) {
                    $f['cleanup']($person, $helper);
                }
                $f['assign']($person, $helper, $request->{$f['form_name']});
            });        
    }

    public function show(Helper $helper, Request $request) {
        $this->authorize('view', $helper);

        $sections = $this->getSections();
        $fields = collect($this->getFields());
        $with_empty_fields = $request->has('empty_fields');

        return view('helpers::show', [
            'helper' => $helper,
            'data' => collect(array_keys($sections))
                ->mapWithKeys(function($section) use($fields, $sections, $helper, $with_empty_fields) {
                    $collection = $fields
                        ->where('section', $section)
                        ->where('overview_only', false)
                        ->where('exclude_show', false)
                        ->filter(function($e){ return !isset($e['authorized_view']) || $e['authorized_view']; })
                        ->map(function($f) use($helper) { 
                            return [
                                'label' => __($f['label_key']),
                                'icon' => $f['icon'],
                                'value' => self::getFieldValue($f, $helper),
                            ];
                        });
                    if (!$with_empty_fields) {
                        $collection = $collection->whereNotIn('value', [null]);
                    }
                    return [ 
                        $sections[$section] => $collection->toArray()
                    ];
                })
                ->toArray(),
        ]);
    }

    public function edit(Helper $helper) {
        $this->authorize('update', $helper);

        $sections = $this->getSections();
        $fields = collect($this->getFields());

        return view('helpers::edit', [
            'helper' => $helper,
            'data' => collect(array_keys($sections))
                ->mapWithKeys(function($section) use($fields, $sections, $helper) {
                    return [ 
                        $sections[$section] => $fields
                            ->where('section', $section)
                            ->filter(function($e){ return !isset($e['authorized_change']) || $e['authorized_change']; })
                            ->filter(function($f){ return isset($f['form_name']) && isset($f['form_type']); })
                            ->map(function($f) use($helper) {
                                $value = is_callable($f['value']) ? $f['value']($helper) : $helper->{$f['value']};
                                return self::createFormField($f, $value);
                            })
                            ->toArray()
                    ];
                })
                ->toArray(),
        ]);
    }

    public function update(Helper $helper, Request $request) {
        $this->authorize('update', $helper);

        $this->validateFormData($request);
        
        $this->applyFormData($request, $helper->person, $helper);

        $helper->save();
        $helper->person->save();

		return redirect()->route('people.helpers.show', $helper)
				->with('success', __('people::people.helper_updated'));		
    }

    public function destroy(Helper $helper) {
        $this->authorize('delete', $helper);

        $helper->delete();
        
        return redirect()->route('people.helpers.index')
            ->with('success', __('people::people.helper_deleted'));
    }

    private static function getFieldValue($field, $helper, $with_html = true) {
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
