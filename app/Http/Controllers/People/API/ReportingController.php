<?php

namespace App\Http\Controllers\People\API;

use App\Http\Controllers\Reporting\BaseReportingController;
use App\Http\Controllers\Traits\ValidatesDateRanges;
use App\Models\People\Person;
use App\Models\People\RevokedCard;
use App\Support\ChartResponseBuilder;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ReportingController extends BaseReportingController
{
    use ValidatesDateRanges;

    /**
     * Nationalities
     */
    public function numbers(Request $request)
    {
        [$dateFrom, $dateTo] = self::getDatePeriodFromRequest($request, 30);

        return [
            [
                __('people.new_registrations_today') => Person::whereDate('created_at', '=', Carbon::today())
                    ->withTrashed()
                    ->count(),
                __('people.new_registrations_yesterday') => Person::whereDate('created_at', '=', Carbon::today()->subDay())
                    ->withTrashed()
                    ->count(),
            ],
            [
                __('people.new_registrations_in_time_period') => Person::whereDate('created_at', '>=', $dateFrom)
                    ->whereDate('created_at', '<=', $dateTo)
                    ->withTrashed()
                    ->count(),
                __('people.total_persons_in_database') => Person::count(),
            ],
            [
                __('people.average_new_registrations_per_day') => Person::getAvgRegistrationsPerDay($dateFrom, $dateTo),
            ],
            [
                __('people.cards_issued') => Person::whereNotNull('card_no')->count(),
                __('people.cards_revoked') => RevokedCard::count(),
            ],
        ];
    }

    /**
     * Nationalities
     */
    public function nationalities()
    {
        return []; Person::getNationalities();
    }

    /**
     * Gender distribution
     */
    public function genderDistribution()
    {
        return Person::getGenderDistribution();
    }

    /**
     * Age distribution
     */
    public function ageDistribution()
    {
        return Person::getAgeDistribution();
    }

    /**
     * Registrations per day
     */
    public function registrationsPerDay(Request $request)
    {
        [$dateFrom, $dateTo] = self::getDatePeriodFromRequest($request, 30);

        $data = Person::getRegistrationsPerDay($dateFrom, $dateTo);

        return (new ChartResponseBuilder())
            ->dataset(__('app.registrations'), collect($data))
            ->build();
    }
}
