<?php

namespace App\Http\Controllers\People\Bank;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Reporting\PeopleReportingController;
use App\Http\Requests\StoreTransactionSettings;
use App\Person;
use Illuminate\Support\Facades\Config;

class SettingsController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    function settings() {
		return view('bank.settings', [
            'people_results_per_page' => \Setting::get('people.results_per_page', Config::get('bank.results_per_page')),
            'frequent_visitor_weeks' => \Setting::get('bank.frequent_visitor_weeks', Config::get('bank.frequent_visitor_weeks')),
            'frequent_visitor_threshold' => \Setting::get('bank.frequent_visitor_threshold', Config::get('bank.frequent_visitor_threshold')),
            'current_num_people' => Person::count(),
            'current_num_frequent_visitors' => PeopleReportingController::getNumberOfFrequentVisitors(),
		]);
    }

	function updateSettings(StoreTransactionSettings $request) {
        \Setting::set('people.results_per_page', $request->people_results_per_page);
        \Setting::set('bank.frequent_visitor_weeks', $request->frequent_visitor_weeks);
        \Setting::set('bank.frequent_visitor_threshold', $request->frequent_visitor_threshold);
		\Setting::save();
		return redirect()->route('bank.index')
                    ->with('success', 'Settings have been updated!');
	}
}
