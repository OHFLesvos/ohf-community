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
                    'Total persons registered' => Person::count(),
                    'Registered in time period' => Person::whereDate('created_at', '>=', $dateFrom)
                        ->whereDate('created_at', '<=', $dateTo)
                        ->count(),
                ],
                [
                    'Registered today' => Person::whereDate('created_at', '=', Carbon::today())
                        ->withTrashed()
                        ->count(),
                    'Registered yesterday' => Person::whereDate('created_at', '=', Carbon::today()->subDay())
                        ->withTrashed()
                        ->count(),
                ],
                [
                    'Average registrations per day' => Person::getAvgRegistrationsPerDay($dateFrom, $dateTo),
                ],
            ],
            'nationalities' => Person::getNationalities(),
            'gender' => Person::getGenderDistribution(),
            'ageDistribution' => Person::getAgeDistribution(),
            'cards' => [
                [
                    'Cards issued' => Person::whereNotNull('card_no')->count(),
                    'Cards revoked' => RevokedCard::count(),
                ]            
            ]
		]);
    }

}
