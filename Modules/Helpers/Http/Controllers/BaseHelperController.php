<?php

namespace Modules\Helpers\Http\Controllers;

use App\Http\Controllers\Controller;

use Modules\People\Entities\Person;

use Modules\Helpers\Entities\Helper;
use Modules\Helpers\Entities\Responsibility;

use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Gate;
use Illuminate\Database\Eloquent\Builder;

use Gumlet\ImageResize;

use Countries;

use Carbon\Carbon;

abstract class BaseHelperController extends Controller
{
    protected static $asylum_request_states = [
        'awaiting_interview',
        'waiting_for_decision',
        'first_rejection',
        'second_rejection',
        'subsidiary_protection',
        'refugee_status',
    ];

    protected static function getTaxNumberStates() {
        return [
            null => __('app.unspecified'),
            'no' => __('app.no'),
            'applied' => __('people::people.applied'),
            'yes' => __('app.yes'),
        ];
    }

    protected function getSections() {
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

    protected function getFields() {
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
                'exclude_show' => false,
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
                'form_validate' => [
                    'nullable',
                    'image'
                ],
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
                    return Countries::getList('en'); 
                },
                'form_validate' => function(){
                    return [
                        'nullable',
                        'max:191',
                        Rule::in(Countries::getList('en'))
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
                'form_validate' => 'required|date',
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
                'value' => function($helper) { return $helper->responsibilities->pluck('name')->all(); },
                'value_html' => function($helper) { 
                    return $helper->responsibilities
                        ->map(function($r){
                            $str = $r->name;
                            if ($r->hasHelpersAltoughNotAvailable) {
                                $str.= ' <span class="text-danger">(' . __('app.not_available') . ')</span>';
                            }
                            if ($r->isCapacityExhausted) {
                                $str.= ' <span class="text-danger">(' . __('app.capacity_exhausted') . ')</span>';
                            }                            
                            return $str;
                        })
                        ->implode('<br>'); 
                },
                'overview' => true,
                'section' => 'occupation',
                'import_labels' => [ 'Project' ],
                'assign' => function($person, $helper, $value) {
                    $selected = [];
                    if ($value != null) {
                        if (!is_array($value)) {
                            $values = [];
                            foreach (preg_split('/(\s*[,\/|]\s*)|(\s+and\s+)/', $value) as $v) {
                                if (preg_match('/^trial$|^trial\\s+|\\s+trial$/i', $v)) {
                                    $helper->work_trial_period = true;
                                } else {
                                    $values[] = $v;
                                }
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
                'form_list' => Responsibility::select('name')
                        ->where('available', true)
                        ->orderBy('name')
                        ->get()
                        ->pluck('name', 'name')
                        ->toArray(),
                'form_validate' => [
                    Rule::in(Responsibility::select('name')->get()->pluck('name')->all())
                ] 
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
                'label_key' => 'helpers::helpers.working_since_days',
                'icon' => null,
                'value' => function($helper) { return $helper->working_since_days; },
                'overview' => false,
                'section' => 'occupation',
                'form_name' => 'working_since_days',
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

    protected function getScopes() {
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

    protected function getGroupings() {
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
                    return Responsibility::has('helpers')
                        ->orderBy('name')
                        ->get()
                        ->pluck('name')
                        ->toArray();
                },
                'query' => function($q, $v) {
                    return $q->whereHas('responsibilities', function (Builder $query) use ($v) {
                        $query->where('name', $v);
                    });
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

    protected function getViews() {
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
                'label' => __('people::people.name_nationality_occupation'),
                'columns' => ['name', 'family_name', 'nickname', 'nationality', 'responsibilities'],
            ],
            'monthly_support' => [
                'label' => __('people::people.monthly_support'),
                'columns' => ['name', 'family_name', 'nickname', 'nationality', 'responsibilities', 'working_since_days', 'monthly_support'],
            ],
            'contact_info' => [
                'label' => __('people::people.contact_info'),
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
    
    protected static function getAllTranslations($key) {
        return collect(language()->allowed())
            ->keys()
            ->map(function($lk) use ($key) { return __($key, [], $lk); });
    }

}
