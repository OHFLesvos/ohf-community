<?php

namespace App\Http\Controllers\People\API;

use App\Http\Controllers\Reporting\BaseReportingController;
use App\Http\Controllers\ValidatesDateRanges;
use App\Models\People\Person;
use Illuminate\Http\Request;

class ReportingController extends BaseReportingController
{
    use ValidatesDateRanges;

    /**
     * Nationalities
     */
    public function nationalities()
    {
        return response()->json([
            'labels' => null,
            'datasets' => collect(Person::getNationalities())
                ->map(fn ($e) => [$e])
                ->toArray(),
        ]);
    }

    /**
     * Gender distribution
     */
    public function genderDistribution()
    {
        $gender = Person::getGenderDistribution();
        return response()->json([
            'labels' => array_keys($gender),
            'datasets' => [array_values($gender)],
        ]);
    }

    /**
     * Age distribution
     */
    public function ageDistribution()
    {
        $data = Person::getAgeDistribution();
        return response()->json([
            'labels' => array_keys($data),
            'datasets' => [array_values($data)],
        ]);
    }

    /**
     * Registrations per day
     */
    public function registrationsPerDay(Request $request)
    {
        [$dateFrom, $dateTo] = self::getDatePeriodFromRequest($request, 30);

        $data = Person::getRegistrationsPerDay($dateFrom, $dateTo);
        return response()->json([
            'labels' => array_keys($data),
            'datasets' => [
                'Registrations' => array_values($data),
            ],
        ]);
    }

}
