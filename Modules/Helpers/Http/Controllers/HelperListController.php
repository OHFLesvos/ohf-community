<?php

namespace Modules\Helpers\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Export\ExportableActions;

use Modules\People\Entities\Person;

use Modules\Helpers\Entities\Helper;
use Modules\Helpers\Http\Requests\ImportHelpers;
use Modules\Helpers\Exports\HelpersExport;
use Modules\Helpers\Imports\HelpersImport;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Storage;

use \Gumlet\ImageResize;

use JeroenDesloovere\VCard\VCard;

use Validator;

use ZipStream\ZipStream;

use Carbon\Carbon;

class HelperListController extends Controller
{
    use ExportableActions;

    private static $asylum_request_states = [
        'awaiting_interview',
        'waiting_for_decision',
        'first_rejection',
        'second_rejection',
        'subsidiary_protection',
        'refugee_status',
    ];

    public static function getTaxNumberStates() {
        return [
            null => __('app.unspecified'),
            'no' => __('app.no'),
            'applied' => __('people::people.applied'),
            'yes' => __('app.yes'),
        ];
    }

    function getSections() {
        return [
            'portrait' => __('people::people.portrait'),
            'general' => __('app.general'),
            'reachability' => __('people::people.reachability'),
            'occupation' => __('people::people.occupation'),
            'identification' => __('people::people.identification'),
            'casework' => __('people::people.casework'),
            'distribution' => __('people::people.distribution'),
        ];
    }

    function getFields() {
        return [
            [
                'label_key' => 'people::people.portrait_picture',
                'icon' => null,
                'value' => function($helper) { return $helper->person->portrait_picture; },
                'value_html' => function($helper) { 
                    return isset($helper->person->portrait_picture) 
                        ? '<img src="' . Storage::url($helper->person->portrait_picture) . '" class="img-fluid">'
                        : null;
                },
                'overview' => false,
                'exclude_export' => true,
                'exclude_show' => true,
                'section' => 'portrait',
                'assign' => function($person, $helper, $value) {
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
                'cleanup' => function($person, $helper) {
                    if ($person->portrait_picture != null) {
                        Storage::delete($person->portrait_picture);
                        $person->portrait_picture = null;
                    }                        
                },
                'form_type' => 'image',
                'form_name' => 'portrait_picture',
                'form_validate' => [ 'nullable', 'image'],
                'form_help' => __('people::people.image_will_be_croped_resized_to_2_3_aspect_ratio'),
            ],
            [
                'label_key' => 'people::people.name',
                'icon' => null,
                'value' => function($helper) { return $helper->person->name; },
                'overview' => true,
                'detail_link' => true,
                'section' => 'general',
                'assign' => function($person, $helper, $value) { $person->name = $value; },
                'form_type' => 'text',
                'form_name' => 'name',
                'form_validate' => [
                    'required',
                    'max:191',
                ],
            ],
            [
                'label_key' => 'people::people.family_name',
                'icon' => null,
                'value' => function($helper) { return $helper->person->family_name; },
                'overview' => true,
                'section' => 'general',
                'import_labels' => [ 'Surname' ],
                'assign' => function($person, $helper, $value) { $person->family_name = $value; },
                'form_type' => 'text',
                'form_name' => 'family_name',
                'form_validate' => [
                    'required',
                    'max:191',
                ],
            ],
            [
                'label_key' => 'people::people.nickname',
                'icon' => null,
                'value' => function($helper) { return $helper->person->nickname; },
                'overview' => true,
                'section' => 'general',
                'assign' => function($person, $helper, $value) { $person->nickname = $value; },
                'form_type' => 'text',
                'form_name' => 'nickname',
                'form_validate' => [
                    'nullable',
                    'max:191',
                ],
            ],
            // [
            //     'label_key' => 'people::people.name',
            //     'icon' => null,
            //     'value' => function($helper) { return $helper->person->fullName; },
            //     'overview' => true,
            //     'overview_only' => true,
            //     'detail_link' => true,
            //     'section' => 'general',
            // ],
            [
                'label_key' => 'people::people.nationality',
                'icon' => 'globe',
                'value' => function($helper) { return $helper->person->nationality; },
                'overview' => true,
                'section' => 'general',
                'assign' => function($person, $helper, $value) { $person->nationality = $value; },
                'form_type' => 'text',
                'form_name' => 'nationality',
                'form_autocomplete' => function() {
                    return \Countries::getList('en'); 
                },
                'form_validate' => function(){
                    return [
                        'nullable',
                        'max:191',
                        Rule::in(\Countries::getList('en'))
                    ];    
                },
            ],
            [
                'label_key' => 'people::people.gender',
                'icon' => null,
                'value' => function($helper) { return $helper->person->gender != null ? ($helper->person->gender == 'f' ? __('app.female') : __('app.male')) : null; },
                'value_html' => function($helper) { return $helper->person->gender != null ? ($helper->person->gender == 'f' ? icon('female') : icon('male')) : null; },
                'overview' => true,
                'section' => 'general',
                'assign' => function($person, $helper, $value) { 
                    $person->gender = ($value != null ? (self::getAllTranslations('app.female')->contains($value) ? 'f' : 'm') : null); 
                },
                'form_type' => 'radio',
                'form_name' => 'gender',
                'form_list' => [ 
                    null => __('app.unspecified'),
                    __('app.male') => __('app.male'),
                    __('app.female') => __('app.female')
                ],
                'form_validate' => 'required', // TODO better validation |in:m,f
            ],
            [
                'label_key' => 'people::people.date_of_birth',
                'icon' => null,
                'value' => function($helper) { return $helper->person->date_of_birth; },
                'overview' => false,
                'section' => 'general',
                'import_labels' => [ 'DOB' ],
                'assign' => function($person, $helper, $value) { $person->date_of_birth = !empty($value) ? Carbon::parse($value) : null; },
                'form_type' => 'text',
                'form_name' => 'date_of_birth',
                'form_placeholder' => 'YYYY-MM-DD',
                'form_validate' => 'nullable|date',
            ],
            [
                'label_key' => 'people::people.age',
                'icon' => null,
                'value' => function($helper) { return $helper->person->age; },
                'overview' => true,
                'section' => 'general',
            ],
            [
                'label_key' => 'people::people.languages',
                'icon' => 'language',
                'value' => function($helper) { return $helper->person->languages != null ? (is_array($helper->person->languages) ? implode(", ", $helper->person->languages) : $helper->person->languages) : null; },
                'value_html' => function($helper) { return $helper->person->languages != null ? (is_array($helper->person->languages) ? implode("<br>", $helper->person->languages) : $helper->person->languages) : null; },
                'overview' => false,
                'section' => 'general',
                'assign' => function($person, $helper, $value) { $person->languages = ($value != null ? array_map('trim', preg_split('/(\s*[,\/|]\s*)|(\s+and\s+)/', $value)) : null); },
                'form_type' => 'text',
                'form_name' => 'languages',
                'form_help' => __('app.separate_by_comma'),
                'form_autocomplete' => function() { 
                    return Person::groupBy('languages')
                        ->orderBy('languages')
                        ->whereNotNull('languages')
                        ->get()
                        ->pluck('languages')
                        ->flatten()
                        ->unique()
                        ->sort()
                        ->toArray();                        
                },
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
                'value_html' => function($helper) { return $helper->whatsapp != null ? whatsapp_link($helper->whatsapp, 'Hello ' . $helper->person->name . "\n") : null; },
                'overview' => false,
                'section' => 'reachability',
                'assign' => function($person, $helper, $value) { $helper->whatsapp = ($value == 'same' ? $helper->local_phone : $value); },
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
                'form_validate' => 'nullable|email',
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
                'label_key' => 'people::people.residence',
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
                'label_key' => 'people::people.pickup_location',
                'icon' => null,
                'value' => 'pickup_location',
                'value_html' => function($helper) { return nl2br($helper->pickup_location); },
                'overview' => false,
                'section' => 'reachability',
                'assign' => function($person, $helper, $value) { $helper->pickup_location = $value; },
                'form_type' => 'text',
                'form_name' => 'pickup_location',
                'form_autocomplete' => function() { 
                    return Helper::groupBy('pickup_location')
                        ->orderBy('pickup_location')
                        ->whereNotNull('pickup_location')
                        ->get()
                        ->pluck('pickup_location')
                        ->flatten()
                        ->unique()
                        ->sort()
                        ->toArray();                        
                },
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
                        $values = [];
                        foreach (preg_split('/(\s*[,\/|]\s*)|(\s+and\s+)/', $value) as $v) {
                            if (preg_match('/^trial$|^trial\\s+|\\s+trial$/i', $v)) {
                                $helper->work_trial_period = true;
                            } else {
                                $values[] = $v;
                            }
                        }
                        $values = array_map('trim', $values);
                    }
                    $helper->responsibilities = $values;
                },
                'form_type' => 'text',
                'form_name' => 'responsibilities',
                'form_help' => __('app.separate_by_comma'),
                'form_autocomplete' => function() { 
                    return Helper::groupBy('responsibilities')
                        ->orderBy('responsibilities')
                        ->whereNotNull('responsibilities')
                        ->get()
                        ->pluck('responsibilities')
                        ->flatten()
                        ->unique()
                        ->sort()
                        ->toArray();
                },
            ],
            [
                'label_key' => 'people::people.trial_period',
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
                'label_key' => 'people::people.application_date',
                'icon' => 'calendar',
                'value' => function($helper) { return optional($helper->work_application_date)->toDateString(); },
                'overview' => false,
                'section' => 'occupation',
                'import_labels' => [ 'Appliction date' ],
                'assign' => function($person, $helper, $value) { $helper->work_application_date = !empty($value) ? Carbon::parse($value) : null; },
                'form_type' => 'date',
                'form_name' => 'application_date',
                'form_validate' => 'nullable|date',
            ],
            [
                'label_key' => 'people::people.rejection_date',
                'icon' => 'calendar',
                'value' => function($helper) { return optional($helper->work_rejection_date)->toDateString(); },
                'overview' => false,
                'section' => 'occupation',
                'assign' => function($person, $helper, $value) { $helper->work_rejection_date = !empty($value) ? Carbon::parse($value) : null; },
                'form_type' => 'date',
                'form_name' => 'rejection_date',
                'form_validate' => 'nullable|date',
            ],
            [
                'label_key' => 'people::people.starting_date',
                'icon' => 'calendar',
                'value' => function($helper) { return optional($helper->work_starting_date)->toDateString(); },
                'overview' => false,
                'section' => 'occupation',
                'import_labels' => [ 'Starting date at OHF' ],
                'assign' => function($person, $helper, $value) { $helper->work_starting_date = !empty($value) ? Carbon::parse($value) : null; },
                'form_type' => 'date',
                'form_name' => 'starting_date',
                'form_validate' => 'nullable|date',
            ],
            [
                'label_key' => 'people::people.leaving_date',
                'icon' => 'calendar',
                'value' => function($helper) { return optional($helper->work_leaving_date)->toDateString(); },
                'overview' => false,
                'section' => 'occupation',
                'assign' => function($person, $helper, $value) { $helper->work_leaving_date = !empty($value) ? Carbon::parse($value) : null; },
                'form_type' => 'date',
                'form_name' => 'leaving_date',
                'form_validate' => 'nullable|date',
            ],
            [
                'label_key' => 'people::people.monthly_support',
                'icon' => 'euro-sign',
                'value' => function($helper) { return $helper->work_monthly_support; },
                'overview' => false,
                'section' => 'occupation',
                'assign' => function($person, $helper, $value) { $helper->work_monthly_support = !empty($value) ? $value : null; },
                'form_type' => 'number',
                'form_name' => 'monthly_support',
                'form_validate' => 'nullable|numeric',
            ],
            [
                'label_key' => 'people::people.background',
                'icon' => null,
                'value' => 'work_background',
                'value_html' => function($helper) { return nl2br($helper->work_background); },
                'overview' => false,
                'section' => 'occupation',
                'import_labels' => [ 'Profession before Lesbos, secret talents, ambitions' ],
                'assign' => function($person, $helper, $value) { $helper->work_background = $value; },
                'form_type' => 'textarea',
                'form_name' => 'background',
            ],
            [
                'label_key' => 'people::people.feedback_wishes',
                'icon' => null,
                'value' => 'work_feedback_wishes',
                'value_html' => function($helper) { return nl2br($helper->work_feedback_wishes); },
                'overview' => false,
                'section' => 'occupation',
                'import_labels' => [ 'Improvements in their OHF work' ],
                'assign' => function($person, $helper, $value) { $helper->work_feedback_wishes = $value; },
                'form_type' => 'textarea',
                'form_name' => 'feedback_wishes',
            ],
            [
                'label_key' => 'people::people.police_number',
                'icon' => 'id-card',
                'value' => function($helper) { return $helper->person->police_no; },
                'overview' => false,
                'prefix' => '05/',
                'section' => 'identification',
                'import_labels' => [ 'Police No.' ],
                'assign' => function($person, $helper, $value) { $val = preg_replace('/^05\//', '', $value); $person->police_no = (!empty($val) ? $val : null); },
                'form_type' => 'number',
                'form_name' => 'police_number',
                'form_validate' => 'nullable|numeric',
            ],
            [
                'label_key' => 'people::people.staff_card_no',
                'icon' => 'id-card',
                'value' => function($helper) { return $helper->person->staff_card_no; },
                'overview' => false,
                'section' => 'identification',
                'assign' => function($person, $helper, $value) { $person->staff_card_no = $value; },
                // 'form_type' => 'number',
                // 'form_name' => 'staff_card_no',
                // 'form_validate' => 'nullable|numeric',
            ],
            [
                'label_key' => 'people::people.has_tax_number',
                'icon' => null,
                'value' => function($helper) { return $helper->has_tax_number; },
                'value' => function($helper) { 
                    return $helper->has_tax_number != null ? self::getTaxNumberStates()[$helper->has_tax_number] : null; 
                },
                'overview' => false,
                'section' => 'identification',
                'import_labels' => [ 'Tax Number' ],
                'assign' => function($person, $helper, $value) { 
                    $helper->has_tax_number = $value;
                },
                'form_type' => 'radio',
                'form_name' => 'has_tax_number',
                'form_list' => collect(self::getTaxNumberStates())->mapWithKeys(function($s, $k){ return [ $k => $s ]; })->toArray(),
                'form_validate' => [ 'nullable', Rule::in(array_keys(self::getTaxNumberStates())) ]
            ],
            [
                'label_key' => 'people::people.endorses_casework',
                'icon' => null,
                'value' => function($helper) {
                    return $helper->endorses_casework !== null ? ($helper->endorses_casework ? __('app.yes') : __('app.no')) : null; 
                },
                'overview' => false,
                'section' => 'casework',
                'assign' => function($person, $helper, $value) { 
                    $helper->endorses_casework = ($value != null ? (self::getAllTranslations('app.yes')->contains($value)) : null); 
                },
                'form_type' => 'radio',
                'form_name' => 'endorses_casework',
                'form_list' => [ 
                    null => __('app.unspecified'),
                    __('app.yes') => __('app.yes'),
                    __('app.no') => __('app.no'),
                ],
                'authorized_view' => Gate::allows('view-helpers-casework'),
                'authorized_change' => Gate::allows('manage-helpers-casework'),
            ],
            [
                'label_key' => 'people::people.case_number',
                'icon' => 'id-card',
                'value' => function($helper) { return $helper->casework_case_number; },
                'overview' => false,
                'section' => 'casework',
                'assign' => function($person, $helper, $value) { $helper->casework_case_number = $value; },
                'form_type' => 'number',
                'form_name' => 'case_number',
                'form_validate' => 'nullable|numeric',
                'authorized_view' => Gate::allows('view-helpers-casework'),
                'authorized_change' => Gate::allows('manage-helpers-casework'),
            ],
            [
                'label_key' => 'people::people.asylum_request_status',
                'icon' => null,
                'value' => function($helper) { 
                    return $helper->casework_asylum_request_status != null ? __('people::people.' . $helper->casework_asylum_request_status) : null; 
                },
                'overview' => false,
                'section' => 'casework',
                'assign' => function($person, $helper, $value) {
                    foreach(self::$asylum_request_states as $s) {
                        if (self::getAllTranslations('people::people.' . $s)->contains($value)) {
                            $helper->casework_asylum_request_status = $s;
                            break;
                        }
                    }
                },
                'form_type' => 'select',
                'form_name' => 'asylum_request_status',
                'form_list' => collect(self::$asylum_request_states)->mapWithKeys(function($s){ return [ __('people::people.' . $s) => __('people::people.' . $s) ]; })->toArray(),
                'form_placeholder' => __('app.select_status'),
                'form_validate' => 'nullable', // TODO better validation
                'authorized_view' => Gate::allows('view-helpers-casework'),
                'authorized_change' => Gate::allows('manage-helpers-casework'),
            ],
            [
                'label_key' => 'people::people.has_geo_restriction',
                'icon' => null,
                'value' => function($helper) { return $helper->casework_has_geo_restriction !== null ? ($helper->casework_has_geo_restriction  ? __('app.yes') : __('app.no')) : null; },
                'overview' => false,
                'section' => 'casework',
                'import_labels' => [ 'Geo Restriction' ],
                'assign' => function($person, $helper, $value) { 
                    $helper->casework_has_geo_restriction = ($value != null ? (self::getAllTranslations('app.yes')->contains($value)) : null); 
                },
                'form_type' => 'radio',
                'form_name' => 'has_geo_restriction',
                'form_list' => [ 
                    null => __('app.unspecified'),
                    __('app.yes') => __('app.yes'),
                    __('app.no') => __('app.no'),
                ],
                'authorized_view' => Gate::allows('view-helpers-casework'),
                'authorized_change' => Gate::allows('manage-helpers-casework'),
            ],
            [
                'label_key' => 'people::people.has_id_card',
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
                'authorized_view' => Gate::allows('view-helpers-casework'),
                'authorized_change' => Gate::allows('manage-helpers-casework'),
            ],
            [
                'label_key' => 'people::people.has_passport',
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
                'authorized_view' => Gate::allows('view-helpers-casework'),
                'authorized_change' => Gate::allows('manage-helpers-casework'),
            ],
            [
                'label_key' => 'people::people.first_interview_date',
                'icon' => null,
                'value' => function($helper) { return optional($helper->casework_first_interview_date)->toDateString(); },
                'overview' => false,
                'section' => 'casework',
                'import_labels' => [ '1st interview date' ],
                'assign' => function($person, $helper, $value) { $helper->casework_first_interview_date = !empty($value) ? Carbon::parse($value) : null; },
                'form_type' => 'date',
                'form_name' => 'first_interview_date',
                'form_validate' => 'nullable|date',
                'authorized_view' => Gate::allows('view-helpers-casework'),
                'authorized_change' => Gate::allows('manage-helpers-casework'),
            ],
            [
                'label_key' => 'people::people.second_interview_date',
                'icon' => null,
                'value' => function($helper) { return optional($helper->casework_second_interview_date)->toDateString(); },
                'overview' => false,
                'section' => 'casework',
                'import_labels' => [ '2nd interview date' ],
                'assign' => function($person, $helper, $value) { $helper->casework_second_interview_date = !empty($value) ? Carbon::parse($value) : null; },
                'form_type' => 'date',
                'form_name' => 'second_interview_date',
                'form_validate' => 'nullable|date',
                'authorized_view' => Gate::allows('view-helpers-casework'),
                'authorized_change' => Gate::allows('manage-helpers-casework'),
            ],
            [
                'label_key' => 'people::people.first_decision_date',
                'icon' => null,
                'value' => function($helper) { return optional($helper->casework_first_decision_date)->toDateString(); },
                'overview' => false,
                'section' => 'casework',
                'import_labels' => [ '1st decision date' ],
                'assign' => function($person, $helper, $value) { $helper->casework_first_decision_date = !empty($value) ? Carbon::parse($value) : null; },
                'form_type' => 'date',
                'form_name' => 'first_decision_date',
                'form_validate' => 'nullable|date',
                'authorized_view' => Gate::allows('view-helpers-casework'),
                'authorized_change' => Gate::allows('manage-helpers-casework'),
            ],
            [
                'label_key' => 'people::people.appeal_date',
                'icon' => null,
                'value' => function($helper) { return optional($helper->casework_appeal_date)->toDateString(); },
                'overview' => false,
                'section' => 'casework',
                'import_labels' => [ 'Appeal date' ],
                'assign' => function($person, $helper, $value) { $helper->casework_appeal_date = !empty($value) ? Carbon::parse($value) : null; },
                'form_type' => 'date',
                'form_name' => 'appeal_date',
                'form_validate' => 'nullable|date',
                'authorized_view' => Gate::allows('view-helpers-casework'),
                'authorized_change' => Gate::allows('manage-helpers-casework'),
            ],
            [
                'label_key' => 'people::people.second_decision_date',
                'icon' => null,
                'value' => function($helper) { return optional($helper->casework_second_decision_date)->toDateString(); },
                'overview' => false,
                'section' => 'casework',
                'import_labels' => [ '2nd decision date' ],
                'assign' => function($person, $helper, $value) { $helper->casework_second_decision_date = !empty($value) ? Carbon::parse($value) : null; },
                'form_type' => 'date',
                'form_name' => 'second_decision_date',
                'form_validate' => 'nullable|date',
                'authorized_view' => Gate::allows('view-helpers-casework'),
                'authorized_change' => Gate::allows('manage-helpers-casework'),
            ],
            [
                'label_key' => 'people::people.vulnerability_assessment_date',
                'icon' => null,
                'value' => function($helper) { return optional($helper->casework_vulnerability_assessment_date)->toDateString(); },
                'overview' => false,
                'section' => 'casework',
                'import_labels' => [ 'Vulnerability assessment date' ],
                'assign' => function($person, $helper, $value) { $helper->casework_vulnerability_assessment_date = !empty($value) ? Carbon::parse($value) : null; },
                'form_type' => 'date',
                'form_name' => 'vulnerability_assessment_date',
                'form_validate' => 'nullable|date',
                'authorized_view' => Gate::allows('view-helpers-casework'),
                'authorized_change' => Gate::allows('manage-helpers-casework'),
            ],
            [
                'label_key' => 'people::people.vulnerability',
                'icon' => null,
                'value' => 'casework_vulnerability',
                'overview' => false,
                'section' => 'casework',
                'assign' => function($person, $helper, $value) { $helper->casework_vulnerability = $value; },
                'form_type' => 'text',
                'form_name' => 'vulnerability',
                'authorized_view' => Gate::allows('view-helpers-casework'),
                'authorized_change' => Gate::allows('manage-helpers-casework'),
            ],
            [
                'label_key' => 'people::people.card_expiry_date',
                'icon' => null,
                'value' => function($helper) { return optional($helper->casework_card_expiry_date)->toDateString(); },
                'overview' => false,
                'section' => 'casework',
                'import_labels' => [ 'Asylum card expiry' ],
                'assign' => function($person, $helper, $value) { $helper->casework_card_expiry_date = !empty($value) ? Carbon::parse($value) : null; },
                'form_type' => 'date',
                'form_name' => 'card_expiry_date',
                'form_validate' => 'nullable|date',
                'authorized_view' => Gate::allows('view-helpers-casework'),
                'authorized_change' => Gate::allows('manage-helpers-casework'),
            ],
            [
                'label_key' => 'people::people.lawyer_name',
                'icon' => null,
                'value' => 'casework_lawyer_name',
                'overview' => false,
                'section' => 'casework',
                'import_labels' => [ 'Lawyer' ],
                'assign' => function($person, $helper, $value) { $helper->casework_lawyer_name = $value != 'No' ? $value : null; },
                'form_type' => 'text',
                'form_name' => 'lawyer_name',
                'authorized_view' => Gate::allows('view-helpers-casework'),
                'authorized_change' => Gate::allows('manage-helpers-casework'),
            ],
            [
                'label_key' => 'people::people.lawyer_phone',
                'icon' => null,
                'value' => 'casework_lawyer_phone',
                'value_html' => function($helper) { return $helper->casework_lawyer_phone != null ? tel_link($helper->casework_lawyer_phone) : null; },
                'overview' => false,
                'section' => 'casework',
                'import_labels' => [ 'Contact' ],
                'assign' => function($person, $helper, $value) { $helper->casework_lawyer_phone = $value; },
                'form_type' => 'text',
                'form_name' => 'lawyer_phone',
                'authorized_view' => Gate::allows('view-helpers-casework'),
                'authorized_change' => Gate::allows('manage-helpers-casework'),
            ],
            [
                'label_key' => 'people::people.lawyer_email',
                'icon' => null,
                'value' => 'casework_lawyer_email',
                'value_html' => function($helper) { return $helper->casework_lawyer_email != null ? email_link($helper->casework_lawyer_email) : null; },
                'overview' => false,
                'section' => 'casework',
                'assign' => function($person, $helper, $value) { $helper->casework_lawyer_email = $value; },
                'form_type' => 'email',
                'form_name' => 'lawyer_email',
                'form_validate' => 'nullable|email',
                'authorized_view' => Gate::allows('view-helpers-casework'),
                'authorized_change' => Gate::allows('manage-helpers-casework'),
            ],
            [
                'label_key' => 'people::people.notes',
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
            [
                'label_key' => 'people::people.shirt_size',
                'icon' => null,
                'value' => function($helper) { return $helper->shirt_size; },
                'overview' => false,
                'section' => 'distribution',
                'assign' => function($person, $helper, $value) { $helper->shirt_size = $value; },
                'form_type' => 'text',
                'form_name' => 'shirt_size',
            ],
            [
                'label_key' => 'people::people.shoe_size',
                'icon' => null,
                'value' => function($helper) { return $helper->shirt_size; },
                'overview' => false,
                'section' => 'distribution',
                'assign' => function($person, $helper, $value) { $helper->shirt_size = $value; },
                'form_type' => 'text',
                'form_name' => 'shoe_size',
            ],
            [
                'label_key' => 'people::people.urgent_needs',
                'icon' => null,
                'value' => function($helper) { return $helper->urgent_needs; },
                'value_html' => function($helper) { return nl2br($helper->urgent_needs); },
                'overview' => false,
                'section' => 'distribution',
                'assign' => function($person, $helper, $value) { $helper->urgent_needs = $value; },
                'form_type' => 'textarea',
                'form_name' => 'urgent_needs',
            ],
            [
                'label_key' => 'people::people.work_needs',
                'icon' => null,
                'value' => function($helper) { return $helper->work_needs; },
                'value_html' => function($helper) { return nl2br($helper->work_needs); },
                'overview' => false,
                'section' => 'distribution',
                'assign' => function($person, $helper, $value) { $helper->work_needs = $value; },
                'form_type' => 'textarea',
                'form_name' => 'work_needs',
            ],
            [
                'label_key' => 'people::people.remarks',
                'icon' => null,
                'value' => function($helper) { return $helper->person->remarks; },
                'value_html' => function($helper) { return nl2br($helper->person->remarks); },
                'overview' => false,
                'section' => 'distribution',
                'assign' => function($person, $helper, $value) { $person->remarks = $value; },
                'form_type' => 'textarea',
                'form_name' => 'remarks',
            ],
        ];
    }

    function getScopes() {
        return collect([
            'active' => [
                'label' => __('people::people.active'),
                'scope' => 'active',
            ],
            'trial' => [
                'label' => __('people::people.trial_period'),
                'scope' => 'trial',
            ],
            'regular' => [
                'label' => __('people::people.regular'),
                'scope' => 'regular',
            ],
            'applicants' => [
                'label' => __('people::people.applicants'),
                'scope' => 'applicants',
            ],
            'alumni' => [
                'label' => __('people::people.alumni'),
                'scope' => 'alumni',
            ],
        ]);
    }

    function getGroupings() {
        return collect([
            'nationalities' => [
                'label' => __('people::people.nationalities'),
                'groups' => function() {
                    return Person::groupBy('nationality')
                        ->orderBy('nationality')
                        ->whereNotNull('nationality')
                        ->get()
                        ->pluck('nationality')
                        ->toArray();
                },
                'query' => function($q, $v) {
                    return $q
                        ->select('helpers.*')
                        ->join('persons', 'helpers.person_id', '=', 'persons.id')
                        ->where('nationality', $v);
                },
            ],
            'languages' => [
                'label' => __('people::people.languages'),
                'groups' => function() {
                    return Person::groupBy('languages')
                        ->orderBy('languages')
                        ->whereNotNull('languages')
                        ->get()
                        ->pluck('languages')
                        ->flatten()
                        ->unique()
                        ->sort()
                        ->toArray();
                },
                'query' => function($q, $v) {
                    return $q
                        ->select('helpers.*')
                        ->join('persons', 'helpers.person_id', '=', 'persons.id')
                        ->where('languages', "like",'%"'.$v.'"%');
                },
            ],
            'gender' => [
                'label' => __('people::people.gender'),
                'groups' => function() {
                    return Person::groupBy('gender')
                        ->orderBy('gender')
                        ->whereNotNull('gender')
                        ->get()
                        ->pluck('gender')
                        ->flatten()
                        ->unique()
                        ->sort()
                        ->push(null)
                        ->toArray();
                },
                'query' => function($q, $v) {
                    return $q
                        ->select('helpers.*')
                        ->join('persons', 'helpers.person_id', '=', 'persons.id')
                        ->where('gender', $v);
                },
                'label_transform'=> function($groups) {
                    return collect($groups)
                        ->map(function($s) {
                            switch ($s) {
                                case 'f':
                                    return __('app.female');
                                case 'm':
                                    return __('app.male');
                                default:
                                    return __('app.unspecified');
                            }
                        });
                },
            ],
            'responsibilities' => [
                'label' => __('app.responsibilities'),
                'groups' => function() {
                    return Helper::groupBy('responsibilities')
                        ->orderBy('responsibilities')
                        ->whereNotNull('responsibilities')
                        ->get()
                        ->pluck('responsibilities')
                        ->flatten()
                        ->unique()
                        ->sort()
                        ->toArray();
                },
                'query' => function($q, $v) {
                    return $q
                        ->where('responsibilities', "like",'%"'.$v.'"%');
                },
            ],
            'pickup_locations' => [
                'label' => __('people::people.pickup_locations'),
                'groups' => function() {
                    return Helper::groupBy('pickup_location')
                        ->orderBy('pickup_location')
                        ->whereNotNull('pickup_location')
                        ->get()
                        ->pluck('pickup_location')
                        ->unique()
                        ->sort()
                        ->push(null)
                        ->toArray();
                },
                'query' => function($q, $v) {
                    return $q
                        ->select('helpers.*')
                        ->join('persons', 'helpers.person_id', '=', 'persons.id')
                        ->where('pickup_location', $v);
                },
                'label_transform'=> function($groups) {
                    return collect($groups)
                        ->map(function($s) {
                            return $s == null ? __('app.unspecified') : $s;
                        });
                },
            ],
            'tax_numbers' => [
                'label' => __('people::people.tax_numbers'),
                'groups' => function() {
                    return Helper::groupBy('has_tax_number')
                        ->orderBy('has_tax_number')
                        ->whereNotNull('has_tax_number')
                        ->get()
                        ->pluck('has_tax_number')
                        ->unique()
                        ->sort()
                        ->push(null)
                        ->toArray();
                },
                'query' => function($q, $v) {
                    return $q
                        ->select('helpers.*')
                        ->join('persons', 'helpers.person_id', '=', 'persons.id')
                        ->where('has_tax_number', $v);
                },
                'label_transform'=> function($groups) {
                    return collect($groups)
                        ->map(function($s) {
                            return self::getTaxNumberStates()[$s];
                        });
                },
            ],
            'asylum_request_status' => [
                'label' => __('people::people.asylum_request_status'),
                'groups' => function() {
                    return Helper::groupBy('casework_asylum_request_status')
                        ->orderBy('casework_asylum_request_status')
                        ->whereNotNull('casework_asylum_request_status')
                        ->get()
                        ->pluck('casework_asylum_request_status')
                        ->flatten()
                        ->unique()
                        ->sort()
                        ->push(null)
                        ->toArray();
                },
                'query' => function($q, $v) {
                    return $q
                        ->where('casework_asylum_request_status', $v);
                },
                'label_transform'=> function($groups) {
                    return collect($groups)
                        ->map(function($s) {
                            return $s == null ? __('app.unspecified') : __('people::people.'.$s);
                        });
                },
                'authorized' => Gate::allows('view-helpers-casework'),
            ],
        ]);
    }

    private function getViews() {
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

    private function getColumnSets() {
        return collect([
            'all' => [
                'label' => __('app.all'),
                'columns' => [],
            ],
            'name_nationality_occupation' => [
                'label' => __('people::people.name_nationality_occupation'),
                'columns' => ['name', 'family_name', 'nickname', 'nationality', 'responsibilities'],
            ],
            'monthly_support' => [
                'label' => __('people::people.monthly_support'),
                'columns' => ['name', 'family_name', 'nickname', 'nationality', 'responsibilities', 'starting_date', 'monthly_support'],
            ],
            'contact_info' => [
                'label' => __('people::people.contact_info'),
                'columns' => ['name', 'family_name', 'nickname', 'local_phone', 'other_phone', 'whatsapp', 'email', 'skype', 'residence'],
            ],
        ]);
    }

    private function getSorters() {
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
                'label' => __('people::people.nationality'),
                'sorting' => 'person.nationality',
            ],
            'gender' => [
                'label' => __('people::people.gender'),
                'sorting' => 'person.gender',
            ],
            'age' => [
                'label' => __('people::people.age'),
                'sorting' => 'person.age',
            ],
            'residence' => [
                'label' => __('people::people.residence'),
                'sorting' => 'residence',
            ],
            'pickup_location' => [
                'label' => __('people::people.pickup_location'),
                'sorting' => 'pickup_location',
            ],
            'work_application_date' => [
                'label' => __('people::people.application_date'),
                'sorting' => 'work_application_date',
            ],
            'work_starting_date' => [
                'label' => __('people::people.starting_date'),
                'sorting' => 'work_starting_date',
            ],
        ]);
    }

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

        Validator::make($request->all(), [
            'person_id' => [
                'required',
                Rule::exists('persons', 'id'),
                function($attribute, $value, $fail) {
                    if (Person::where('id', $value)->has('helper')->first() != null) {
                        return $fail(__('people::people.helper_already_exists'));
                    }
                },
            ],
        ])->validate();

        $person = Person::find($request->person_id);
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

    function exportAuthorize()
    {
        $this->authorize('export', Helper::class);
    }

    function exportView(): string
    {
        return 'helpers::export';
    }

    function exportViewArgs(): array
    {
        return [
            'scopes' => $this->getScopes()->mapWithKeys(function($s, $k){
                return [ $k => $s['label'] ];
            })->toArray(),
            'scope' => $this->getScopes()->keys()->first(),
            'columnt_sets' => $this->getColumnSets()->mapWithKeys(function($s, $k){
                return [ $k => $s['label'] ];
            })->toArray(),
            'columnt_set' => $this->getColumnSets()->keys()->first(),
            'sorters' => $this->getSorters()->mapWithKeys(function($s, $k){
                return [ $k => $s['label'] ];
            })->toArray(),
            'sorting' => $this->getSorters()->keys()->first(),
        ];
    }

    function exportValidateArgs(): array
    {
        return [
            'scope' => [
                'required', 
                Rule::in($this->getScopes()->keys()->toArray()),
            ],
            'column_set' => [
                'required', 
                Rule::in($this->getColumnSets()->keys()->toArray()),
            ],
            'sorting' => [
                'required', 
                Rule::in($this->getSorters()->keys()->toArray()),
            ],
            'orientation' => [
                'required',
                'in:portrait,landscape',
            ],
            'include_portraits' => 'boolean',
        ];
    }

    function exportFilename(Request $request): string
    {
        $scope = $this->getScopes()[$request->scope];
        return __('helpers::helpers.helpers') .'_' . $scope['label'] .'_' . Carbon::now()->toDateString();
    }

    function exportExportable(Request $request)
    {
        $columnSet = $this->getColumnSets()[$request->column_set];
        $fields = self::filterFieldsByColumnSet($this->getFields(), $columnSet);
        $scope = $this->getScopes()[$request->scope];
        $sorting = $this->getSorters()[$request->sorting];       

        $export = new HelpersExport($fields, $scope['scope']);
        $export->setOrientation($request->orientation);
        $export->setSorting($sorting['sorting']);
        return $export;
    }

    private static function filterFieldsByColumnSet(array $fields, array $columnSet) {
        return collect($fields)
            ->where('overview_only', false)
            ->where('exclude_export', false)
            ->filter(function($e){ 
                return !isset($e['authorized_view']) || $e['authorized_view']; 
            })
            ->filter(function($e) use($columnSet){
                if (count($columnSet['columns']) > 0) {
                    if (isset($e['form_name'])) {
                        return in_array($e['form_name'], $columnSet['columns']);
                    }
                    return false;
                }
                return true;
            });
    }

    protected function exportDownload(Request $request, $export, $file_name, $file_ext) {
        // Download as ZIP with portraits
        if (isset($request->include_portraits)) {
            $zip = new ZipStream($file_name . '.zip');
            $temp_file = 'temp/' . uniqid() . '.' . $file_ext;
            $export->store($temp_file);
            $zip->addFileFromPath($file_name . '.' . $file_ext, storage_path('app/' . $temp_file));
            Storage::delete($temp_file);
            foreach ($helpers as $helper) {
                if (isset($helper->person->portrait_picture)) {
                    $picture_path = storage_path('app/'.$helper->person->portrait_picture);
                    if (is_file($picture_path)) {
                        $ext = pathinfo($picture_path, PATHINFO_EXTENSION);
                        $zip->addFileFromPath('portraits/' . $helper->person->fullName . '.' . $ext , $picture_path);
                    }
                }
            }
            $zip->finish();
        } 
        // Download as simple spreadsheet
        else {
            return $export->download($file_name . '.' . $file_ext);
        }
    }

    function import() {
        $this->authorize('import', Helper::class);

        return view('helpers::import');
    }

    function doImport(ImportHelpers $request) {
        $this->authorize('import', Helper::class);

        $fields = self::getImportFields($this->getFields());

        $importer = new HelpersImport($fields);
        $importer->import($request->file('file'));

		return redirect()->route('people.helpers.index')
				->with('success', __('app.import_successful'));		
    }

    private static function getImportFields($fields) {
        return collect($fields)
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
    }

    private static function getAllTranslations($key) {
        return collect(language()->allowed())
            ->keys()
            ->map(function($lk) use ($key) { return __($key, [], $lk); });
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

    /**
     * Download vcard
     * 
     * @param  \Modules\Helpers\Entities\Helper  $helper
     * @return \Illuminate\Http\Response
     */
    function vcard(Helper $helper)
    {
        $this->authorize('view', $helper);

        // define vcard
        $vcard = new VCard();
        // if ($helper->company != null) {
        //     $vcard->addCompany($helper->company);
        // }
        $vcard->addCompany(Config::get('app.name'));

        if ($helper->person->family_name != null || $helper->person->name != null) {
            $vcard->addName($helper->person->family_name, $helper->person->name, '', '', '');
        }
        if ($helper->email != null) {
            $vcard->addEmail($helper->email);
        }
        if ($helper->local_phone != null) {
            $vcard->addPhoneNumber(preg_replace('/[^+0-9]/', '', $helper->local_phone), 'HOME');
        }
        if ($helper->whatsapp != null && $helper->local_phone != $helper->whatsapp) {
            $vcard->addPhoneNumber(preg_replace('/[^+0-9]/', '', $helper->whatsapp), 'WORK');
        }

        if (isset($helper->person->portrait_picture)) {
            $contents = Storage::get($helper->person->portrait_picture);
            if ($contents != null) {
                $vcard->addPhotoContent($contents);
            }
        }

        // return vcard as a download
        return $vcard->download();
    }
    
    public function filterPersons(Request $request) {
        $qry = Person::limit(10)
            ->whereHas('helper', function ($query) {
                $query->whereNotNull('work_starting_date')
                ->whereDate('work_starting_date', '<=', Carbon::today())
                ->where(function($q){
                    return $q->whereNull('work_leaving_date')
                        ->orWhereDate('work_leaving_date', '>=', Carbon::today());
                });
            })
            ->orderBy('family_name')
            ->orderBy('name');
        if (isset($request->query()['query'])) {
            $qry->where('search', 'LIKE', '%' . $request->query()['query'] . '%');
        }
        $persons = $qry->get()
            ->map(function($e){ 
                $val = $e->family_name . ' '. $e->name;
                if (!empty($e->date_of_birth)) {
                    $val.= ', ' . $e->date_of_birth . ' (age ' . $e->age . ')';
                }
                if (!empty($e->nationality)) {
                    $val.= ', ' . $e->nationality;
                }
                return [
                    'value' => $val,
                    'data' => $e->getRouteKey(),
                ]; 
            });
        return response()->json(["suggestions" => $persons]);
    }

    function report() {

        return view('helpers::report', [

        ]);
    }

    /**
     * Gender distribution
     */
    function genderDistribution() {
        $gender = self::getGenderDistribution();
        return response()->json([
            'labels' => array_keys($gender),
            'datasets' => [array_values($gender)],
        ]);
    }

    private static function getGenderDistribution() {
        return collect(
            Person::whereHas('helper', function($query){
                    return self::getActiveHelperQuery($query);
                })
                ->select('gender', \DB::raw('count(*) as total'))
                ->groupBy('gender')
                ->whereNotNull('gender')
                ->get()
            )->mapWithKeys(function($i) {
                if ($i['gender'] == 'm') {
                    $label = __('app.male');
                } else if ($i['gender'] == 'f') {
                    $label = __('app.female');
                } else {
                    $label = $i['gender'];
                }
                return [$label =>  $i['total']];
            })
            ->toArray();
    }

    /**
     * Nationalities
     */
    function nationalities() {
        $nationalities = self::getNationalities();
        return response()->json([
            'labels' => array_keys($nationalities),
            'datasets' => [array_values($nationalities)],
        ]);
    }

    private static function getNationalities($limit = 10) {
        $nationalities = collect(
            Person::whereHas('helper', function($query){
                    return self::getActiveHelperQuery($query);
                })
                ->select('nationality', \DB::raw('count(*) as total'))
                ->groupBy('nationality')
                ->whereNotNull('nationality')
                ->orderBy('total', 'DESC')
                ->get()
            )->mapWithKeys(function($i){
                return [$i['nationality'] =>  $i['total']];
            });
        $data = $nationalities->slice(0, $limit)->toArray();
        $other = $nationalities->slice($limit)->reduce(function ($carry, $item) {
                 return $carry + $item;
            });
        if ($other > 0) {
            $data['Other'] = $other;
        }
        return $data;
    }

    /**
     * Nationalities
     */
    function ages() {
        $nationalities = self::getAges();
        return response()->json([
            'labels' => array_keys($nationalities),
            'datasets' => [ __('people::people.persons') => array_values($nationalities)],
        ]);
    }

    private static function getAges() {
        $ages = [];
        $minAge = optional(Person::whereHas('helper', function($query){
            return self::getActiveHelperQuery($query);
        })->orderBy('date_of_birth', 'desc')
        ->limit(1)
        ->first()
        )->age;
        $maxAge = optional(Person::whereHas('helper', function($query){
            return self::getActiveHelperQuery($query);
        })->orderBy('date_of_birth', 'asc')
        ->limit(1)
        ->first()
        )->age;
        if ($minAge != null && $maxAge != null) {
        foreach (range($minAge, $maxAge) as $r) {
            $ages[$r.' '] = null;
        }
        return collect($ages)->merge(
            Person::whereHas('helper', function($query){
                    return self::getActiveHelperQuery($query);
                })
                ->select('date_of_birth', \DB::raw('TIMESTAMPDIFF(YEAR, date_of_birth, CURDATE()) AS age'), \DB::raw('count(*) as total'))
                ->groupBy('age')
                ->whereNotNull('date_of_birth')
                ->orderBy('age', 'asc')
                ->get()
            ->mapWithKeys(function($i){
                return [$i['age'].' ' => $i['total']];
            }))
            ->toArray();
            
        } else {
            return [];
        }
    }

    private static function getActiveHelperQuery($query) {
        return $query
            ->whereNotNull('work_starting_date')
            ->whereDate('work_starting_date', '<=', Carbon::today())
            ->where(function($q){
                return $q->whereNull('work_leaving_date')
                    ->orWhereDate('work_leaving_date', '>=', Carbon::today());
            });
    }
}
