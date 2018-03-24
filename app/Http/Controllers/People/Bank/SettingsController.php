<?php

namespace App\Http\Controllers\People\Bank;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Reporting\PeopleReportingController;
use App\Http\Requests\People\Bank\StoreTransactionSettings;
use App\Person;
use Illuminate\Support\Facades\Config;

class SettingsController extends Controller
{
    /**
     * View for configuring settings.
     * 
     * @return \Illuminate\Http\Response
     */
    function settings() {
        return view('bank.settings', [
            'people_results_per_page' => \Setting::get('people.results_per_page', Config::get('bank.results_per_page')),
            'frequent_visitor_weeks' => \Setting::get('bank.frequent_visitor_weeks', Config::get('bank.frequent_visitor_weeks')),
            'frequent_visitor_threshold' => \Setting::get('bank.frequent_visitor_threshold', Config::get('bank.frequent_visitor_threshold')),
            'current_num_people' => Person::count(),
            'current_num_frequent_visitors' => PeopleReportingController::getNumberOfFrequentVisitors(),
        ]);
    }

    /**
     * Update settings
     * 
     * @param  \App\Http\Requests\People\Bank\StoreTransactionSettings  $request
     * @return \Illuminate\Http\Response
     */
    function updateSettings(StoreTransactionSettings $request) {
        \Setting::set('people.results_per_page', $request->people_results_per_page);
        \Setting::set('bank.frequent_visitor_weeks', $request->frequent_visitor_weeks);
        \Setting::set('bank.frequent_visitor_threshold', $request->frequent_visitor_threshold);
        \Setting::save();
        return redirect()->route('bank.withdrawal')
            ->with('success', __('people.settings_updated'));
    }
}
