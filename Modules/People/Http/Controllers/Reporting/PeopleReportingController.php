<?php

namespace Modules\People\Http\Controllers\Reporting;

use App\Http\Controllers\Reporting\BaseReportingController;

use Modules\People\Entities\Person;
use Modules\People\Entities\RevokedCard;

use Illuminate\Http\Request;

use Carbon\Carbon;

class PeopleReportingController extends BaseReportingController
{
    /**
     * Index
     */
    function index(Request $request)
    {
        list($dateFrom, $dateTo) = parent::getDatePeriodFromRequest($request, 30);

        return view('people::reporting.people', [
            'dateFrom' => $dateFrom,
            'dateTo' => $dateTo,
            'people' => [
                [
                    __('people::people.new_registrations_today') => Person::whereDate('created_at', '=', Carbon::today())
                        ->withTrashed()
                        ->count(),
                    __('people::people.new_registrations_yesterday') => Person::whereDate('created_at', '=', Carbon::today()->subDay())
                        ->withTrashed()
                        ->count(),
                ],
                [
                    __('people::people.new_registrations_in_time_period') => Person::whereDate('created_at', '>=', $dateFrom)
                        ->whereDate('created_at', '<=', $dateTo)
                        ->withTrashed()
                        ->count(),
                    __('people::people.total_persons_in_database') => Person::count(),
                ],
                [
                    __('people::people.average_new_registrations_per_day') => Person::getAvgRegistrationsPerDay($dateFrom, $dateTo),
                ],
            ],
            'nationalities' => Person::getNationalities(),
            'gender' => Person::getGenderDistribution(),
            'ageDistribution' => Person::getAgeDistribution(),
            'cards' => [
                [
                    __('people::people.cards_issued') => Person::whereNotNull('card_no')->count(),
                    __('people::people.cards_revoked') => RevokedCard::count(),
                ]            
            ]
		]);
    }

}
