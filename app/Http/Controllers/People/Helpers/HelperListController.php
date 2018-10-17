<?php

namespace App\Http\Controllers\People\Helpers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Helper;
use Carbon\Carbon;

class HelperListController extends Controller
{
    function getSections() {
        return [
            'general' => __('app.general'),
            'reachability' => __('people.reachability'),
            'occupation' => __('people.occupation'),
            'identification' => __('people.identification'),
            'casework' => __('people.casework'),
        ];
    }

    function getFields() {
        return [
            [
                'label' => __('people.name'),
                'icon' => null,
                'value' => function($helper) { return $helper->person->name; },
                'overview' => false,
                'section' => 'general',
            ],
            [
                'label' => __('people.family_name'),
                'icon' => null,
                'value' => function($helper) { return $helper->person->family_name; },
                'overview' => false,
                'section' => 'general',
            ],
            [
                'label' => __('people.nickname'),
                'icon' => null,
                'value' => function($helper) { return $helper->person->nickname; },
                'overview' => false,
                'section' => 'general',
            ],
            [
                'label' => __('people.name'),
                'icon' => null,
                'value' => function($helper) { return $helper->person->fullName; },
                'overview' => true,
                'overview_only' => true,
                'detail_link' => true,
                'section' => 'general',
            ],
            [
                'label' => __('people.nationality'),
                'icon' => 'globe',
                'value' => function($helper) { return $helper->person->nationality; },
                'overview' => true,
                'section' => 'general',
            ],
            [
                'label' => __('people.date_of_birth'),
                'icon' => null,
                'value' => function($helper) { return $helper->person->date_of_birth; },
                'overview' => false,
                'section' => 'general',
            ],
            [
                'label' => __('people.age'),
                'icon' => null,
                'value' => function($helper) { return $helper->person->age; },
                'overview' => true,
                'section' => 'general',
            ],
            [
                'label' => __('people.gender'),
                'icon' => null,
                'value' => function($helper) { return $helper->person->gender == 'f' ? __('people.female') : __('people.male'); },
                'value_html' => function($helper) { return $helper->person->gender == 'f' ? icon('female') : icon('male'); },
                'overview' => true,
                'section' => 'general',
            ],
            [
                'label' => __('people.languages'),
                'icon' => 'language',
                'value' => function($helper) { return $helper->person->languages; },
                'overview' => false,
                'section' => 'general',
            ],
            [
                'label' => __('app.local_phone'),
                'icon' => 'phone',
                'value' => 'local_phone',
                'value_html' => function($helper) { return $helper->local_phone != null ? tel_link($helper->local_phone) : null; },
                'overview' => false,
                'section' => 'reachability',
            ],
            [
                'label' => __('app.other_phone'),
                'icon' => 'phone',
                'value' => 'other_phone',
                'value_html' => function($helper) { return $helper->other_phone != null ? tel_link($helper->other_phone) : null; },
                'overview' => false,
                'section' => 'reachability',
            ],
            [
                'label' => __('app.whatsapp'),
                'icon' => 'whatsapp',
                'value' => 'whatsapp',
                'value_html' => function($helper) { return $helper->whatsapp != null ? whatsapp_link($helper->whatsapp) : null; },
                'overview' => false,
                'section' => 'reachability',
            ],
            [
                'label' => __('app.email'),
                'icon' => 'envelope',
                'value' => 'email',
                'value_html' => function($helper) { return $helper->email != null ? email_link($helper->email) : null; },
                'overview' => false,
                'section' => 'reachability',
            ],
            [
                'label' => __('app.skype'),
                'icon' => 'skype',
                'value' => 'skype',
                'value_html' => function($helper) { return $helper->skype != null ? skype_link($helper->skype) : null; },
                'overview' => false,
                'section' => 'reachability',
            ],
            [
                'label' => __('people.residence'),
                'icon' => null,
                'value' => 'residence',
                'value_html' => function($helper) { return nl2br($helper->residence); },
                'overview' => false,
                'section' => 'reachability',
            ],
            [
                'label' => __('app.responsibilities'),
                'icon' => null,
                'value' => function($helper) { return implode(", ", $helper->responsibilities); },
                'value_html' => function($helper) { return implode("<br>", $helper->responsibilities); },
                'overview' => true,
                'section' => 'occupation',
            ],
            [
                'label' => __('people.application_date'),
                'icon' => 'calendar',
                'value' => function($helper) { return optional($helper->work_application_date)->toDateString(); },
                'overview' => false,
                'section' => 'occupation',
            ],
            [
                'label' => __('people.rejection_date'),
                'icon' => 'calendar',
                'value' => function($helper) { return optional($helper->work_rejection_date)->toDateString(); },
                'overview' => false,
                'section' => 'occupation',
            ],
            [
                'label' => __('people.starting_date'),
                'icon' => 'calendar',
                'value' => function($helper) { return optional($helper->work_starting_date)->toDateString(); },
                'overview' => false,
                'section' => 'occupation',
            ],
            [
                'label' => __('people.leaving_date'),
                'icon' => 'calendar',
                'value' => function($helper) { return optional($helper->work_leaving_date)->toDateString(); },
                'overview' => false,
                'section' => 'occupation',
            ],
            [
                'label' => __('people.trial_period'),
                'icon' => 'calendar',
                'value' => function($helper) { return $helper->work_trial_period !== null ? ($helper->work_trial_period ? __('app.yes') : __('app.no')) : null ; },
                'overview' => false,
                'section' => 'occupation',
            ],
            [
                'label' => __('people.background'),
                'icon' => null,
                'value' => 'background',
                'value_html' => function($helper) { return nl2br($helper->work_background); },
                'overview' => false,
                'section' => 'occupation',
            ],
            [
                'label' => __('people.improvements'),
                'icon' => null,
                'value' => 'improvements',
                'value_html' => function($helper) { return nl2br($helper->work_improvements); },
                'overview' => false,
                'section' => 'occupation',
            ],
            [
                'label' => __('people.police_number'),
                'icon' => 'id-card',
                'value' => function($helper) { return $helper->person->police_no; },
                'overview' => false,
                'prefix' => '05/',
                'section' => 'identification',
            ],
            [
                'label' => __('people.case_number'),
                'icon' => 'id-card',
                'value' => function($helper) { return $helper->person->case_no; },
                'overview' => false,
                'section' => 'identification',
            ],
            [
                'label' => __('people.medical_number'),
                'icon' => 'id-card',
                'value' => function($helper) { return $helper->person->medical_no; },
                'overview' => false,
                'section' => 'identification',
            ],
            [
                'label' => __('people.registration_number'),
                'icon' => 'id-card',
                'value' => function($helper) { return $helper->person->registration_no; },
                'overview' => false,
                'section' => 'identification',
            ],
            [
                'label' => __('people.section_card_number'),
                'icon' => 'id-card',
                'value' => function($helper) { return $helper->person->section_card_no; },
                'overview' => false,
                'section' => 'identification',
            ],
            [
                'label' => __('people.temporary_number'),
                'icon' => 'id-card',
                'value' => function($helper) { return $helper->person->temp_no; },
                'overview' => false,
                'section' => 'identification',
            ],
            [
                'label' => __('people.casework'),
                'icon' => null,
                'value' => function($helper) { return $helper->casework !== null ? ($helper->casework ? __('app.yes') : __('app.no')) : null; },
                'overview' => false,
                'section' => 'casework',
            ],
            [
                'label' => __('app.status'),
                'icon' => null,
                'value' => function($helper) { return __('people.' . $helper->casework_status); },
                'overview' => false,
                'section' => 'casework',
            ],
            [
                'label' => __('people.geo_restriction'),
                'icon' => null,
                'value' => function($helper) { return $helper->casework_geo_restriction !== null ? ($helper->casework_geo_restriction  ? __('app.yes') : __('app.no')) : null; },
                'overview' => false,
                'section' => 'casework',
            ],
            [
                'label' => __('people.interview_date'),
                'icon' => null,
                'value' => function($helper) { return optional($helper->casework_interview_date)->toDateString(); },
                'overview' => false,
                'section' => 'casework',
            ],
            [
                'label' => __('people.first_decision_date'),
                'icon' => null,
                'value' => function($helper) { return optional($helper->casework_first_decision_date)->toDateString(); },
                'overview' => false,
                'section' => 'casework',
            ],
            [
                'label' => __('people.appeal_date'),
                'icon' => null,
                'value' => function($helper) { return optional($helper->appeal_date)->toDateString(); },
                'overview' => false,
                'section' => 'casework',
            ],
            [
                'label' => __('people.second_decision_date'),
                'icon' => null,
                'value' => function($helper) { return optional($helper->casework_second_decision_date)->toDateString(); },
                'overview' => false,
                'section' => 'casework',
            ],
            [
                'label' => __('people.vulnerable_date'),
                'icon' => null,
                'value' => function($helper) { return optional($helper->casework_vulnerable_date)->toDateString(); },
                'overview' => false,
                'section' => 'casework',
            ],
            [
                'label' => __('people.vulnerable'),
                'icon' => null,
                'value' => function($helper) { return $helper->casework_vulnerable !== null ? ($helper->casework_vulnerable ? __('app.yes') : __('app.no')) : null; },
                'overview' => false,
                'section' => 'casework',
            ],
            [
                'label' => __('people.card_expiry_date'),
                'icon' => null,
                'value' => function($helper) { return optional($helper->casework_card_expiry_date)->toDateString(); },
                'overview' => false,
                'section' => 'casework',
            ],
            [
                'label' => __('people.lawyer_name'),
                'icon' => null,
                'value' => 'casework_lawyer_name',
                'overview' => false,
                'section' => 'casework',
            ],
            [
                'label' => __('people.lawyer_phone'),
                'icon' => null,
                'value' => 'casework_lawyer_phone',
                'value_html' => function($helper) { return $helper->casework_lawyer_phone != null ? phone_link($helper->casework_lawyer_phone) : null; },
                'overview' => false,
                'section' => 'casework',
            ],
            [
                'label' => __('people.lawyer_email'),
                'icon' => null,
                'value' => 'casework_lawyer_email',
                'value_html' => function($helper) { return $helper->casework_lawyer_email != null ? email_link($helper->casework_lawyer_email) : null; },
                'overview' => false,
                'section' => 'casework',
            ],
            [
                'label' => __('people.remarks'),
                'icon' => null,
                'value' => function($helper) { return $helper->person->remarks; },
                'value_html' => function($helper) { return nl2br($helper->person->remarks); },
                'overview' => false,
                'section' => 'general',
            ],
        ];
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

    public function index(Request $request) {
        // TODO authorization

        $fields = collect($this->getFields())->where('overview', true);

        return view('people.helpers.index', [
            'fields' => collect($this->getFields())->where('overview', true),
            'data' => Helper::get()->load('person')->sortBy('persons.name')->mapWithKeys(function($helper) use($fields) {
                return [ $helper->id => $fields->map(function($field) use($helper){
                    return [
                        'detail_link' => isset($field['detail_link']) && $field['detail_link'],
                        'value' => self::getFieldValue($field, $helper),
                    ];
                })->toArray()];
            }),
        ]);
    }

    public function show(Helper $helper, Request $request) {
        // TODO authorization

        $sections = $this->getSections();
        $fields = collect($this->getFields());
        $with_empty_fields = $request->has('empty_fields');

        return view('people.helpers.show', [
            'helper' => $helper,
            'data' => collect(array_keys($sections))
                ->mapWithKeys(function($section) use($fields, $sections, $helper, $with_empty_fields) {
                    $collection = $fields
                        ->where('section', $section)
                        ->where('overview_only', false)
                        ->map(function($f) use($helper) { 
                            return [
                                'label' => $f['label'],
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

    public function export(Request $request) {
        // TODO authorization

        \Excel::create(__('people.helpers').'_' . Carbon::now()->toDateString(), function($excel) {
            $excel->sheet(__('people.helpers'), function($sheet) {
                $sheet->setOrientation('landscape');
                $sheet->setPageMargin(0.25);
                $sheet->setAllBorders('thin');
                $sheet->setFitToPage(false);
                $sheet->setFontSize(10);
                $sheet->setFreeze('D2');
                $sheet->loadView('people.helpers.export',[
                    'fields' => collect($this->getFields())->where('overview_only', false),
                    'helpers' => Helper::get()->load('person')->sortBy('persons.name'),
                ]);
            });
            $excel->getActiveSheet()->setAutoFilter(
                $excel->getActiveSheet()->calculateWorksheetDimension()
            );
        })->export('xlsx');
    }
}
