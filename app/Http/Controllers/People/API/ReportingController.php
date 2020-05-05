<?php

namespace App\Http\Controllers\People\API;

use App\Http\Controllers\Reporting\BaseReportingController;
use App\Http\Controllers\Traits\ValidatesDateRanges;
use App\Models\People\Person;
use App\Support\ChartResponseBuilder;
use Illuminate\Http\Request;

class ReportingController extends BaseReportingController
{
    use ValidatesDateRanges;

    /**
     * Nationalities
     */
    public function nationalities()
    {
        return Person::getNationalities();
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
