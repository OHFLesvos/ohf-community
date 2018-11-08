<?php

namespace App\Http\Controllers\People\Bank;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Config;

use App\Http\Controllers\Reporting\PeopleReportingController;
use App\Person;

class SettingsController extends Controller
{
    private function getSections() {
        return [
            'display_settings' => __('people.display_settings'),
            'frequent_visitors' => __('people.frequent_visitors'),
        ];
    }

    private function getSettings() {
        return [
            'people.results_per_page' => [
                'default' => Config::get('bank.results_per_page'),
                'form_type' => 'number',
                'form_args' => [ 'min' => 1 ],
                'form_validate' => 'required|numeric|min:1',
                'label_key' => 'app.number_results_per_page',
                'section' => 'display_settings',
            ],
            'bank.frequent_visitor_weeks' => [
                'default' => Config::get('bank.frequent_visitor_weeks'),
                'form_type' => 'number',
                'form_args' => [ 'min' => 1 ],
                'form_validate' => 'required|numeric|min:1',
                'label_key' => 'people.number_of_weeks',
                'section' => 'frequent_visitors',
                'include_pre' => 'bank.settings.frequent_visitors_explanation',
            ],
            'bank.frequent_visitor_threshold' => [
                'default' => Config::get('bank.frequent_visitor_threshold'),
                'form_type' => 'number',
                'form_args' => [ 'min' => 1 ],
                'form_validate' => 'required|numeric|min:1',
                'label_key' => 'people.min_number_of_visits',
                'section' => 'frequent_visitors',
                'include_post' => [ 'bank.settings.frequent_visitors_affected', [
                    'current_num_people' => Person::count(),
                    'current_num_frequent_visitors' => PeopleReportingController::getNumberOfFrequentVisitors(),
                ] ],
            ],
        ];
    }

    /**
     * View for configuring settings.
     * 
     * @return \Illuminate\Http\Response
     */
    function settings() {
        return view('bank.settings', [
            'sections' => $this->getSections(),
            'fields' => collect($this->getSettings())
                ->mapWithKeys(function($e, $k){ return [ 
                    str_slug($k) => [
                        'value' => \Setting::get($k, $e['default']),
                        'type' => $e['form_type'],
                        'label' => __($e['label_key']),
                        'section' => $e['section'],
                        'args' => $e['form_args'],
                        'include_pre' => $e['include_pre'] ?? null,
                        'include_post' => $e['include_post'] ?? null,
                    ]
                ]; }),
        ]);
    }

    /**
     * Update settings
     * 
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    function updateSettings(Request $request) {

        // Validate
        $request->validate(
            collect($this->getSettings())
                ->filter(function($f){ 
                    return isset($f['form_validate']);
                })
                ->mapWithKeys(function($f, $k) {
                    $rules = is_callable($f['form_validate']) ? $f['form_validate']() : $f['form_validate'];
                    return [str_slug($k) => $rules];
                })
                ->toArray()
        );

        // Update
        foreach($this->getSettings() as $field_key => $field) {
            \Setting::set($field_key, $request->{str_slug($field_key)});        
        }
        \Setting::save();
        
        // Redirect
        return redirect()->route('bank.withdrawal')
            ->with('success', __('people.settings_updated'));
    }
}
