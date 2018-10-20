<?php

namespace App\Http\Controllers\People\Helpers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Helper;
use App\Person;
use Carbon\Carbon;
use Illuminate\Support\Facades\Config;

class HelperListController extends Controller
{
    private static $asylum_request_states = [
        'awaiting_interview',
        'first_rejection',
        'second_rejection',
        'subsidiary_protection',
        'refugee_status',
        'deported',
        'voluntarily_returned',
    ];

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
                'label_key' => 'people.name',
                'icon' => null,
                'value' => function($helper) { return $helper->person->name; },
                'overview' => false,
                'section' => 'general',
                'assign' => function($person, $helper, $value) { $person->name = $value; },
                'form_type' => 'text',
                'form_name' => 'name',
            ],
            [
                'label_key' => 'people.family_name',
                'icon' => null,
                'value' => function($helper) { return $helper->person->family_name; },
                'overview' => false,
                'section' => 'general',
                'import_labels' => [ 'Surname' ],
                'assign' => function($person, $helper, $value) { $person->family_name = $value; },
                'form_type' => 'text',
                'form_name' => 'family_name',
            ],
            [
                'label_key' => 'people.nickname',
                'icon' => null,
                'value' => function($helper) { return $helper->person->nickname; },
                'overview' => false,
                'section' => 'general',
                'assign' => function($person, $helper, $value) { $person->nickname = $value; },
                'form_type' => 'text',
                'form_name' => 'nickname',
            ],
            [
                'label_key' => 'people.name',
                'icon' => null,
                'value' => function($helper) { return $helper->person->fullName; },
                'overview' => true,
                'overview_only' => true,
                'detail_link' => true,
                'section' => 'general',
            ],
            [
                'label_key' => 'people.nationality',
                'icon' => 'globe',
                'value' => function($helper) { return $helper->person->nationality; },
                'overview' => true,
                'section' => 'general',
                'assign' => function($person, $helper, $value) { $person->nationality = $value; },
                'form_type' => 'text',
                'form_name' => 'nationality',
                'form_autocomplete' => function() { return \Countries::getList('en'); },
            ],
            [
                'label_key' => 'people.date_of_birth',
                'icon' => null,
                'value' => function($helper) { return $helper->person->date_of_birth; },
                'overview' => false,
                'section' => 'general',
                'import_labels' => [ 'DOB' ],
                'assign' => function($person, $helper, $value) { $person->date_of_birth = !empty($value) ? Carbon::parse($value) : null; },
                'form_type' => 'text',
                'form_name' => 'date_of_birth',
                'form_placeholder' => 'YYYY-MM-DD',
            ],
            [
                'label_key' => 'people.age',
                'icon' => null,
                'value' => function($helper) { return $helper->person->age; },
                'overview' => true,
                'section' => 'general',
            ],
            [
                'label_key' => 'people.gender',
                'icon' => null,
                'value' => function($helper) { return $helper->person->gender != null ? ($helper->person->gender == 'f' ? __('people.female') : __('people.male')) : null; },
                'value_html' => function($helper) { return $helper->person->gender != null ? ($helper->person->gender == 'f' ? icon('female') : icon('male')) : null; },
                'overview' => true,
                'section' => 'general',
                'assign' => function($person, $helper, $value) { 
                    $helper->person->gender = ($value != null ? (self::getAllTranslations('app.female')->contains($value) ? 'f' : 'm') : null); 
                },
                'form_type' => 'radio',
                'form_name' => 'gender',
                'form_list' => [ 
                    null => __('app.unspecified'),
                    __('people.male') => __('people.male'),
                    __('people.female') => __('people.female')
                ],
            ],
            [
                'label_key' => 'people.languages',
                'icon' => 'language',
                'value' => function($helper) { return $helper->person->languages != null ? implode(", ", $helper->person->languages) : null; },
                'value_html' => function($helper) { return $helper->person->languages != null ? implode("<br>", $helper->person->languages) : null; },
                'overview' => false,
                'section' => 'general',
                'assign' => function($person, $helper, $value) { $person->languages = ($value != null ? preg_split('/(\s*[,\/|]\s*)|(\s+and\s+)/', $value) : null); },
                'form_type' => 'text',
                'form_name' => 'languages',
                'form_help' => __('app.separate_by_comma'),
            ],
            [
                'label_key' => 'app.local_phone',
                'icon' => 'phone',
                'value' => 'local_phone',
                'value_html' => function($helper) { return $helper->local_phone != null ? tel_link($helper->local_phone) : null; },
                'overview' => false,
                'section' => 'reachability',
                'import_labels' => [ 'Greek No.' ],
                'assign' => function($person, $helper, $value) { $helper->local_phone = $value; },
                'form_type' => 'text',
                'form_name' => 'local_phone',
            ],
            [
                'label_key' => 'app.other_phone',
                'icon' => 'phone',
                'value' => 'other_phone',
                'value_html' => function($helper) { return $helper->other_phone != null ? tel_link($helper->other_phone) : null; },
                'overview' => false,
                'section' => 'reachability',
                'import_labels' => [ 'Other No.' ],
                'assign' => function($person, $helper, $value) { $helper->other_phone = $value; },
                'form_type' => 'text',
                'form_name' => 'other_phone',
            ],
            [
                'label_key' => 'app.whatsapp',
                'icon' => 'whatsapp',
                'value' => 'whatsapp',
                'value_html' => function($helper) { return $helper->whatsapp != null ? whatsapp_link($helper->whatsapp) : null; },
                'overview' => false,
                'section' => 'reachability',
                'assign' => function($person, $helper, $value) { $helper->whatsapp = ($value == 'same' ? $helper->local_phone : $helper->whatsapp); },
                'form_type' => 'text',
                'form_name' => 'whatsapp',
            ],
            [
                'label_key' => 'app.email',
                'icon' => 'envelope',
                'value' => 'email',
                'value_html' => function($helper) { return $helper->email != null ? email_link($helper->email) : null; },
                'overview' => false,
                'section' => 'reachability',
                'assign' => function($person, $helper, $value) { $helper->email = $value; },
                'form_type' => 'email',
                'form_name' => 'email',
            ],
            [
                'label_key' => 'app.skype',
                'icon' => 'skype',
                'value' => 'skype',
                'value_html' => function($helper) { return $helper->skype != null ? skype_link($helper->skype) : null; },
                'overview' => false,
                'section' => 'reachability',
                'assign' => function($person, $helper, $value) { $helper->skype = $value; },
                'form_type' => 'text',
                'form_name' => 'skype',
            ],
            [
                'label_key' => 'people.residence',
                'icon' => null,
                'value' => 'residence',
                'value_html' => function($helper) { return nl2br($helper->residence); },
                'overview' => false,
                'section' => 'reachability',
                'assign' => function($person, $helper, $value) { $helper->residence = $value; },
                'form_type' => 'textarea',
                'form_name' => 'residence',
            ],
            [
                'label_key' => 'app.responsibilities',
                'icon' => null,
                'value' => function($helper) { return $helper->responsibilities != null ? implode(", ", $helper->responsibilities) : null; },
                'value_html' => function($helper) { return $helper->responsibilities != null ? implode("<br>", $helper->responsibilities) : null; },
                'overview' => true,
                'section' => 'occupation',
                'import_labels' => [ 'Project' ],
                'assign' => function($person, $helper, $value) { 
                    $values = null;
                    if ($value != null) {
                        foreach (preg_split('/(\s*[,\/|]\s*)|(\s+and\s+)/', $value) as $v) {
                            if (preg_match('/^trial$|^trial\\s+|\\s+trial$/i', $v)) {
                                $helper->work_trial_period = true;
                            } else {
                                $values[] = $v;
                            }
                        }
                    }
                    $helper->responsibilities = $values;
                },
                'form_type' => 'text',
                'form_name' => 'responsibilities',
                'form_help' => __('app.separate_by_comma'),
            ],
            [
                'label_key' => 'people.trial_period',
                'icon' => 'calendar',
                'value' => function($helper) { return $helper->work_trial_period !== null ? ($helper->work_trial_period ? __('app.yes') : __('app.no')) : null ; },
                'overview' => false,
                'section' => 'occupation',
                'assign' => function($person, $helper, $value) { 
                    $helper->work_trial_period = ($value != null ? (self::getAllTranslations('app.yes')->contains($value)) : null); 
                },
                'form_type' => 'radio',
                'form_name' => 'trial_period',
                'form_list' => [ 
                    null => __('app.unspecified'),
                    __('app.yes') => __('app.yes'),
                    __('app.no') => __('app.no'),
                ],
            ],
            [
                'label_key' => 'people.application_date',
                'icon' => 'calendar',
                'value' => function($helper) { return optional($helper->work_application_date)->toDateString(); },
                'overview' => false,
                'section' => 'occupation',
                'assign' => function($person, $helper, $value) { $helper->work_application_date = !empty($value) ? Carbon::parse($value) : null; },
                'form_type' => 'date',
                'form_name' => 'application_date',
            ],
            [
                'label_key' => 'people.rejection_date',
                'icon' => 'calendar',
                'value' => function($helper) { return optional($helper->work_rejection_date)->toDateString(); },
                'overview' => false,
                'section' => 'occupation',
                'assign' => function($person, $helper, $value) { $helper->work_rejection_date = !empty($value) ? Carbon::parse($value) : null; },
                'form_type' => 'date',
                'form_name' => 'rejection_date',
            ],
            [
                'label_key' => 'people.starting_date',
                'icon' => 'calendar',
                'value' => function($helper) { return optional($helper->work_starting_date)->toDateString(); },
                'overview' => false,
                'section' => 'occupation',
                'import_labels' => [ 'Starting date at OHF' ],
                'assign' => function($person, $helper, $value) { $helper->work_starting_date = !empty($value) ? Carbon::parse($value) : null; },
                'form_type' => 'date',
                'form_name' => 'starting_date',
            ],
            [
                'label_key' => 'people.leaving_date',
                'icon' => 'calendar',
                'value' => function($helper) { return optional($helper->work_leaving_date)->toDateString(); },
                'overview' => false,
                'section' => 'occupation',
                'assign' => function($person, $helper, $value) { $helper->work_leaving_date = !empty($value) ? Carbon::parse($value) : null; },
                'form_type' => 'date',
                'form_name' => 'leaving_date',
            ],
            [
                'label_key' => 'people.background',
                'icon' => null,
                'value' => 'background',
                'value_html' => function($helper) { return nl2br($helper->work_background); },
                'overview' => false,
                'section' => 'occupation',
                'import_labels' => [ 'Profession before Lesbos, secret talents, ambitions' ],
                'assign' => function($person, $helper, $value) { $helper->work_background = $value; },
                'form_type' => 'textarea',
                'form_name' => 'background',
            ],
            [
                'label_key' => 'people.improvements',
                'icon' => null,
                'value' => 'improvements',
                'value_html' => function($helper) { return nl2br($helper->work_improvements); },
                'overview' => false,
                'section' => 'occupation',
                'import_labels' => [ 'Improvements in their OHF work' ],
                'assign' => function($person, $helper, $value) { $helper->work_improvements = $value; },
                'form_type' => 'textarea',
                'form_name' => 'improvements',
            ],
            [
                'label_key' => 'people.police_number',
                'icon' => 'id-card',
                'value' => function($helper) { return $helper->person->police_no; },
                'overview' => false,
                'prefix' => '05/',
                'section' => 'identification',
                'import_labels' => [ 'Police No.' ],
                'assign' => function($person, $helper, $value) { $val = preg_replace('/^05\//', '', $value); $person->police_no = (!empty($val) ? $val : null); },
                'form_type' => 'number',
                'form_name' => 'police_number',
                // PREfix
            ],
            [
                'label_key' => 'people.case_number',
                'icon' => 'id-card',
                'value' => function($helper) { return $helper->person->case_no; },
                'overview' => false,
                'section' => 'identification',
                'assign' => function($person, $helper, $value) { $person->case_no = $value; },
                'form_type' => 'number',
                'form_name' => 'case_number',
            ],
            [
                'label_key' => 'people.medical_number',
                'icon' => 'id-card',
                'value' => function($helper) { return $helper->person->medical_no; },
                'overview' => false,
                'section' => 'identification',
                'assign' => function($person, $helper, $value) { $person->medical_no = $value; },
                'form_type' => 'number',
                'form_name' => 'medical_number',
            ],
            [
                'label_key' => 'people.registration_number',
                'icon' => 'id-card',
                'value' => function($helper) { return $helper->person->registration_no; },
                'overview' => false,
                'section' => 'identification',
                'assign' => function($person, $helper, $value) { $person->registration_no = $value; },
                'form_type' => 'number',
                'form_name' => 'registration_number',
            ],
            [
                'label_key' => 'people.section_card_number',
                'icon' => 'id-card',
                'value' => function($helper) { return $helper->person->section_card_no; },
                'overview' => false,
                'section' => 'identification',
                'assign' => function($person, $helper, $value) { $person->section_card_no = $value; },
                'form_type' => 'number',
                'form_name' => 'section_card_number',
            ],
            [
                'label_key' => 'people.temporary_number',
                'icon' => 'id-card',
                'value' => function($helper) { return $helper->person->temp_no; },
                'overview' => false,
                'section' => 'identification',
                'assign' => function($person, $helper, $value) { $person->temp_no = $value; },
                'form_type' => 'number',
                'form_name' => 'temporary_number',
            ],
            [
                'label_key' => 'people.casework',
                'icon' => null,
                'value' => function($helper) {
                    return $helper->casework !== null ? ($helper->casework ? __('app.yes') : __('app.no')) : null; 
                },
                'overview' => false,
                'section' => 'casework',
                'assign' => function($person, $helper, $value) { 
                    $helper->casework = ($value != null ? (self::getAllTranslations('app.yes')->contains($value)) : null); 
                },
                'form_type' => 'radio',
                'form_name' => 'casework',
                'form_list' => [ 
                    null => __('app.unspecified'),
                    __('app.yes') => __('app.yes'),
                    __('app.no') => __('app.no'),
                ],
            ],
            [
                'label_key' => 'people.asylum_request_status',
                'icon' => null,
                'value' => function($helper) { 
                    return $helper->casework_asylum_request_status != null ? __('people.' . $helper->casework_asylum_request_status) : null; 
                },
                'overview' => false,
                'section' => 'casework',
                'assign' => function($person, $helper, $value) {
                    foreach(self::$asylum_request_states as $s) {
                        if (self::getAllTranslations('people.' . $s)->contains($value)) {
                            $helper->casework_asylum_request_status = $s;
                            break;
                        }
                    }
                },
                'form_type' => 'select',
                'form_name' => 'asylum_request_status',
                'form_list' => collect(self::$asylum_request_states)->mapWithKeys(function($s){ return [ __('people.' . $s) => __('people.' . $s) ]; })->toArray(),
                'form_placeholder' => __('app.select_status'),
            ],
            [
                'label_key' => 'people.geo_restriction',
                'icon' => null,
                'value' => function($helper) { return $helper->casework_geo_restriction !== null ? ($helper->casework_geo_restriction  ? __('app.yes') : __('app.no')) : null; },
                'overview' => false,
                'section' => 'casework',
                'import_labels' => [ 'Geo Restriction' ],
                'assign' => function($person, $helper, $value) { 
                    $helper->casework_geo_restriction = ($value != null ? (self::getAllTranslations('app.yes')->contains($value)) : null); 
                },
                'form_type' => 'radio',
                'form_name' => 'geo_restriction',
                'form_list' => [ 
                    null => __('app.unspecified'),
                    __('app.yes') => __('app.yes'),
                    __('app.no') => __('app.no'),
                ],
            ],
            [
                'label_key' => 'people.has_id_card',
                'icon' => null,
                'value' => function($helper) { return $helper->casework_has_id_card !== null ? ($helper->casework_has_id_card  ? __('app.yes') : __('app.no')) : null; },
                'overview' => false,
                'section' => 'casework',
                'import_labels' => [ 'Has ID Card' ],
                'assign' => function($person, $helper, $value) { 
                    $helper->casework_has_id_card = ($value != null ? (self::getAllTranslations('app.yes')->contains($value)) : null); 
                },
                'form_type' => 'radio',
                'form_name' => 'has_id_card',
                'form_list' => [ 
                    null => __('app.unspecified'),
                    __('app.yes') => __('app.yes'),
                    __('app.no') => __('app.no'),
                ],
            ],
            [
                'label_key' => 'people.has_passport',
                'icon' => null,
                'value' => function($helper) { return $helper->casework_has_passport !== null ? ($helper->casework_has_passport  ? __('app.yes') : __('app.no')) : null; },
                'overview' => false,
                'section' => 'casework',
                'assign' => function($person, $helper, $value) { 
                    $helper->casework_has_passport = ($value != null ? (self::getAllTranslations('app.yes')->contains($value)) : null); 
                },
                'form_type' => 'radio',
                'form_name' => 'has_passport',
                'form_list' => [ 
                    null => __('app.unspecified'),
                    __('app.yes') => __('app.yes'),
                    __('app.no') => __('app.no'),
                ],
            ],
            [
                'label_key' => 'people.first_interview_date',
                'icon' => null,
                'value' => function($helper) { return optional($helper->casework_first_interview_date)->toDateString(); },
                'overview' => false,
                'section' => 'casework',
                'import_labels' => [ '1st interview date' ],
                'assign' => function($person, $helper, $value) { $helper->casework_first_interview_date = !empty($value) ? Carbon::parse($value) : null; },
                'form_type' => 'date',
                'form_name' => 'first_interview_date',
            ],
            [
                'label_key' => 'people.second_interview_date',
                'icon' => null,
                'value' => function($helper) { return optional($helper->casework_second_interview_date)->toDateString(); },
                'overview' => false,
                'section' => 'casework',
                'import_labels' => [ '2nd interview date' ],
                'assign' => function($person, $helper, $value) { $helper->casework_second_interview_date = !empty($value) ? Carbon::parse($value) : null; },
                'form_type' => 'date',
                'form_name' => 'second_interview_date',
            ],
            [
                'label_key' => 'people.first_decision_date',
                'icon' => null,
                'value' => function($helper) { return optional($helper->casework_first_decision_date)->toDateString(); },
                'overview' => false,
                'section' => 'casework',
                'import_labels' => [ '1st decision date' ],
                'assign' => function($person, $helper, $value) { $helper->casework_first_decision_date = !empty($value) ? Carbon::parse($value) : null; },
                'form_type' => 'date',
                'form_name' => 'first_decision_date',
            ],
            [
                'label_key' => 'people.appeal_date',
                'icon' => null,
                'value' => function($helper) { return optional($helper->casework_appeal_date)->toDateString(); },
                'overview' => false,
                'section' => 'casework',
                'import_labels' => [ 'Appeal date' ],
                'assign' => function($person, $helper, $value) { $helper->casework_appeal_date = !empty($value) ? Carbon::parse($value) : null; },
                'form_type' => 'date',
                'form_name' => 'appeal_date',
            ],
            [
                'label_key' => 'people.second_decision_date',
                'icon' => null,
                'value' => function($helper) { return optional($helper->casework_second_decision_date)->toDateString(); },
                'overview' => false,
                'section' => 'casework',
                'import_labels' => [ '2nd decision date' ],
                'assign' => function($person, $helper, $value) { $helper->casework_second_decision_date = !empty($value) ? Carbon::parse($value) : null; },
                'form_type' => 'date',
                'form_name' => 'second_decision_date',
            ],
            [
                'label_key' => 'people.vulnerability_assessment_date',
                'icon' => null,
                'value' => function($helper) { return optional($helper->casework_vulnerability_assessment_date)->toDateString(); },
                'overview' => false,
                'section' => 'casework',
                'import_labels' => [ 'Vulnerability assessment date' ],
                'assign' => function($person, $helper, $value) { $helper->casework_vulnerability_assessment_date = !empty($value) ? Carbon::parse($value) : null; },
                'form_type' => 'date',
                'form_name' => 'vulnerability_assessment_date',
            ],
            [
                'label_key' => 'people.vulnerability',
                'icon' => null,
                'value' => 'casework_vulnerability',
                'overview' => false,
                'section' => 'casework',
                'assign' => function($person, $helper, $value) { $helper->casework_vulnerability = $value; },
                'form_type' => 'text',
                'form_name' => 'vulnerability',
            ],
            [
                'label_key' => 'people.card_expiry_date',
                'icon' => null,
                'value' => function($helper) { return optional($helper->casework_card_expiry_date)->toDateString(); },
                'overview' => false,
                'section' => 'casework',
                'import_labels' => [ 'Asylum card expiry' ],
                'assign' => function($person, $helper, $value) { $helper->casework_card_expiry_date = !empty($value) ? Carbon::parse($value) : null; },
                'form_type' => 'date',
                'form_name' => 'card_expiry_date',
            ],
            [
                'label_key' => 'people.lawyer_name',
                'icon' => null,
                'value' => 'casework_lawyer_name',
                'overview' => false,
                'section' => 'casework',
                'import_labels' => [ 'Lawyer' ],
                'assign' => function($person, $helper, $value) { $helper->casework_lawyer_name = $value != 'No' ? $value : null; },
                'form_type' => 'text',
                'form_name' => 'lawyer_name',
            ],
            [
                'label_key' => 'people.lawyer_phone',
                'icon' => null,
                'value' => 'casework_lawyer_phone',
                'value_html' => function($helper) { return $helper->casework_lawyer_phone != null ? tel_link($helper->casework_lawyer_phone) : null; },
                'overview' => false,
                'section' => 'casework',
                'import_labels' => [ 'Contact' ],
                'assign' => function($person, $helper, $value) { $helper->casework_lawyer_phone = $value; },
                'form_type' => 'text',
                'form_name' => 'lawyer_phone',
            ],
            [
                'label_key' => 'people.lawyer_email',
                'icon' => null,
                'value' => 'casework_lawyer_email',
                'value_html' => function($helper) { return $helper->casework_lawyer_email != null ? email_link($helper->casework_lawyer_email) : null; },
                'overview' => false,
                'section' => 'casework',
                'assign' => function($person, $helper, $value) { $helper->casework_lawyer_email = $value; },
                'form_type' => 'email',
                'form_name' => 'lawyer_email',
            ],
            [
                'label_key' => 'people.notes',
                'icon' => null,
                'value' => function($helper) { return $helper->notes; },
                'value_html' => function($helper) { return nl2br($helper->notes); },
                'overview' => false,
                'section' => 'general',
                'import_labels' => [ 'Notes' ],
                'assign' => function($person, $helper, $value) { $helper->notes = $value; },
                'form_type' => 'textarea',
                'form_name' => 'notes',
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
            'fields' => $fields->map(function($f){
                return [
                    'label' => __($f['label_key']),
                    'icon' => $f['icon'],
                ];
            }),
            'data' => Helper::get()->load('person')
                //->sortBy('persons.name')
                ->mapWithKeys(function($helper) use($fields) {
                    return [ $helper->id => $fields
                        ->map(function($field) use($helper){
                            return [
                                'detail_link' => isset($field['detail_link']) && $field['detail_link'],
                                'value' => self::getFieldValue($field, $helper),
                            ];
                        })
                        ->toArray()];
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
        // TODO authorization

        $sections = $this->getSections();
        $fields = collect($this->getFields());

        return view('people.helpers.edit', [
            'helper' => $helper,
            'data' => collect(array_keys($sections))
                ->mapWithKeys(function($section) use($fields, $sections, $helper) {
                    return [ 
                        $sections[$section] => $fields
                            ->where('section', $section)
                            ->filter(function($f){ return isset($f['form_name']) && isset($f['form_type']); })
                            ->map(function($f) use($helper) { 
                                return [
                                    'label' => __($f['label_key']),
                                    'name' => $f['form_name'],
                                    'type' => $f['form_type'],
                                    'prefix' => $f['prefix'] ?? null,
                                    'placeholder' => $f['form_placeholder'] ?? null,
                                    'help' => $f['form_help'] ?? null,
                                    'list' => $f['form_list'] ?? null,
                                    'autocomplete' => isset($f['form_autocomplete']) && is_callable($f['form_autocomplete']) ? $f['form_autocomplete']() : null,
                                    'value' => is_callable($f['value']) ? $f['value']($helper) : $helper->{$f['value']},
                                ];
                            })
                            ->toArray()
                    ];
                })
                ->toArray(),
        ]);
    }

    public function update(Helper $helper, Request $request) {
        // TODO authorization

        collect($this->getFields())
            ->filter(function($f){ 
                return isset($f['form_name']) && isset($f['form_type']) 
                    && isset($f['assign']) && is_callable($f['assign']);
            })
            ->each(function($f) use($helper, $request) {
                $f['assign']($helper->person, $helper, $request->{$f['form_name']});
            });
        $helper->save();
        $helper->person->save();

		return redirect()->route('people.helpers.show', $helper)
				->with('success', __('people.helper_updated'));		
    }

    public function destroy(Helper $helper) {
        // TODO authorization

        $helper->delete();
        
        return redirect()->route('people.helpers.index')
            ->with('success', __('people.helper_deleted'));
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
                    'fields' => collect($this->getFields())
                        ->where('overview_only', false),
                    'helpers' => Helper::get()->load('person')
                        // TODO ->sortBy('persons.name'),
                ]);
            });
            $excel->getActiveSheet()->setAutoFilter(
                $excel->getActiveSheet()->calculateWorksheetDimension()
            );
        })->export('xlsx');
    }

    function import() {
        //$this->authorize('create', Helper::class);

        return view('people.helpers.import');
    }

    function doImport(Request $request) {
        // TODO $this->authorize('create', Helper::class);

        // TODO validation using Request
        $this->validate($request, [
            'file' => 'required|file',
        ]);
        $file = $request->file('file');

        Config::set('excel.import.heading', 'original');
        \Excel::selectSheets()->load($file, function($reader) {
            
            $fields = collect($this->getFields())
                ->where('overview_only', false)
                ->filter(function($f){ return isset($f['assign']) && is_callable($f['assign']); })
                ->map(function($f){
                    return [
                        'labels' => self::getAllTranslations($f['label_key'])
                            ->concat(isset($f['import_labels']) && is_array($f['import_labels']) ? $f['import_labels'] : [])
                            ->map(function($l){ return strtolower($l); }),
                        'assign' => $f['assign'],
                    ];
                });

            $sheet_titles = self::getAllTranslations('people.helpers');
            $reader->each(function($sheet) use($sheet_titles, $fields) {
                if ($sheet_titles->contains($sheet->getTitle())) {
                    $sheet->each(function($row) use($fields) {

                        $person = new Person();
                        $helper = new Helper();

                        self::assignImportedValues($row, $fields, $helper, $person);

                        if (isset($person->name) && isset($person->family_name)) {
                            $existing_person = Person::where('name', $person->name)
                                ->where('family_name', $person->family_name)
                                ->where('nationality', $person->nationality)
                                ->where('date_of_birth', $person->date_of_birth)
                                ->first();
                            if ($existing_person != null) {
                                echo "Found existing {$person->name} {$person->family_name}<br>";
                                $existing_helper = $existing_person->helper;
                                $new_helper = false;
                                if ($existing_helper == null) {
                                    $existing_helper = new Helper();
                                    $new_helper = true;
                                }
                                self::assignImportedValues($row, $fields, $existing_helper, $existing_person);
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
                        
                    });
                }
            });
        });
		return redirect()->route('people.helpers.index')
				->with('success', __('app.import_successful'));		
    }

    private static function assignImportedValues($row, $fields, $helper, $person) {
        $row->each(function($value, $label) use($fields, $helper, $person) {
            if ($value !== null && $value != 'N/A') {
                $fields->each(function($f) use($helper, $person, $label, $value) {
                    if ($f['labels']->containsStrict(strtolower($label))) {
                        try {
                            $f['assign']($person, $helper, $value);
                        } catch(\Exception $e) {
                            // echo "Exception for <b>'$value'</b>: " . $e->getMessage()."<br><br>";
                            // ignored
                        }
                    }
                });
            }
        });
    }

    private static function getAllTranslations($key) {
        return collect(language()->allowed())
            ->keys()
            ->map(function($lk) use ($key) { return __($key, [], $lk); });
    }

}
