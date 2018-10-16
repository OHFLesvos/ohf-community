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
            'casework' => __('people.casework'),
        ];
    }

    function getFields() {
        return [
            [
                'label' => __('people.name'),
                'icon' => null,
                'value' => function($helper) { return $helper->person->name; },
                'overview' => true,
                'detail_link' => true,
                'section' => 'general',
            ],
            [
                'label' => __('people.family_name'),
                'icon' => null,
                'value' => function($helper) { return $helper->person->family_name; },
                'overview' => true,
                'detail_link' => true,
                'section' => 'general',
            ],
            [
                'label' => __('people.nickname'),
                'icon' => null,
                'value' => function($helper) { return $helper->person->nickname; },
                'overview' => true,
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
                'label' => __('app.local_phone'),
                'icon' => 'phone',
                'value' => 'local_phone',
                'value_html' => function($helper) { return $helper->local_phone != null ? tel_link($helper->local_phone) : '-'; },
                'overview' => false,
                'section' => 'reachability',
            ],
            [
                'label' => __('app.other_phone'),
                'icon' => 'phone',
                'value' => 'other_phone',
                'value_html' => function($helper) { return $helper->other_phone != null ? tel_link($helper->other_phone) : '-'; },
                'overview' => false,
                'section' => 'reachability',
            ],
            [
                'label' => __('app.whatsapp'),
                'icon' => 'whatsapp',
                'value' => 'whatsapp',
                'value_html' => function($helper) { return $helper->whatsapp != null ? whatsapp_link($helper->whatsapp) : '-'; },
                'overview' => false,
                'section' => 'reachability',
            ],
            [
                'label' => __('app.email'),
                'icon' => 'envelope',
                'value' => 'email',
                'value_html' => function($helper) { return $helper->email != null ? email_link($helper->email) : '-'; },
                'overview' => false,
                'section' => 'reachability',
            ],
            [
                'label' => __('app.skype'),
                'icon' => 'skype',
                'value' => 'skype',
                'value_html' => function($helper) { return $helper->skype != null ? skype_link($helper->skype) : '-'; },
                'overview' => false,
                'section' => 'reachability',
            ],
            [
                'label' => __('people.police_number'),
                'icon' => 'id-card',
                'value' => function($helper) { return $helper->person->police_no; },
                'overview' => false,
                'prefix' => '05/',
                'section' => 'casework',
            ],
            [
                'label' => __('people.case_number'),
                'icon' => 'id-card',
                'value' => function($helper) { return $helper->person->case_no; },
                'overview' => false,
                'section' => 'casework',
            ],
            [
                'label' => __('people.medical_number'),
                'icon' => 'id-card',
                'value' => function($helper) { return $helper->person->medical_no; },
                'overview' => false,
                'section' => 'casework',
            ],
            [
                'label' => __('people.registration_number'),
                'icon' => 'id-card',
                'value' => function($helper) { return $helper->person->registration_no; },
                'overview' => false,
                'section' => 'casework',
            ],
            [
                'label' => __('people.section_card_number'),
                'icon' => 'id-card',
                'value' => function($helper) { return $helper->person->section_card_no; },
                'overview' => false,
                'section' => 'casework',
            ],
            [
                'label' => __('people.temporary_number'),
                'icon' => 'id-card',
                'value' => function($helper) { return $helper->person->temp_no; },
                'overview' => false,
                'section' => 'casework',
            ],
            [
                'label' => __('people.languages'),
                'icon' => 'language',
                'value' => function($helper) { return $helper->person->languages; },
                'overview' => false,
                'section' => 'occupation',
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
                'value' => function($helper) { return optional($helper->application_date)->toDateString(); },
                'overview' => false,
                'section' => 'occupation',
            ],
            [
                'label' => __('people.rejection_date'),
                'icon' => 'calendar',
                'value' => function($helper) { return optional($helper->rejection_date)->toDateString(); },
                'overview' => false,
                'section' => 'occupation',
            ],
            [
                'label' => __('people.starting_date'),
                'icon' => 'calendar',
                'value' => function($helper) { return optional($helper->starting_date)->toDateString(); },
                'overview' => false,
                'section' => 'occupation',
            ],
            [
                'label' => __('people.leaving_date'),
                'icon' => 'calendar',
                'value' => function($helper) { return optional($helper->leaving_date)->toDateString(); },
                'overview' => false,
                'section' => 'occupation',
            ],
            [
                'label' => __('people.trial_period'),
                'icon' => 'calendar',
                'value' => function($helper) { return $helper->trial_period ? __('app.yes') : __('app.no') ; },
                'overview' => false,
                'section' => 'occupation',
            ],
            [
                'label' => __('people.remarks'),
                'icon' => null,
                'value' => function($helper) { return $helper->person->remarks; },
                'overview' => false,
                'section' => 'general',
            ],
        ];
    }

    public function index(Request $request) {
        // TODO authorization

        return view('people.helpers.index', [
            'fields' => collect($this->getFields())->where('overview', true),
            'helpers' => Helper::get()->load('person')->sortBy('persons.name'),
        ]);
    }

    public function show(Helper $helper, Request $request) {
        // TODO authorization

        return view('people.helpers.show', [
            'fields' => $this->getFields(),
            'sections' => $this->getSections(),
            'helper' => $helper,
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
                    'fields' => $this->getFields(),
                    'helpers' => Helper::get()->load('person')->sortBy('persons.name'),
                ]);
            });
            $excel->getActiveSheet()->setAutoFilter(
                $excel->getActiveSheet()->calculateWorksheetDimension()
            );
        })->export('xlsx');
    }
}
