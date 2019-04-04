<?php

namespace Modules\Bank\Http\Controllers;

use App\Person;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\Reporting\PeopleReportingController;

use Illuminate\Support\Facades\Config;

class BankSettingsController extends SettingsController
{
    protected function getSections() {
        return [
            'display_settings' => __('people.display_settings'),
            'frequent_visitors' => __('people.frequent_visitors'),
        ];
    }

    protected function getSettings() {
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

    protected function getUpdateRouteName() {
        return 'bank.settings.update';
    }

    protected function getRedirectRouteName() {
        return 'bank.withdrawal';
    }

}
