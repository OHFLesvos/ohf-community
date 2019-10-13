<?php

namespace Modules\People\Http\Controllers\Reporting;

use App\Http\Controllers\Reporting\BaseReportingController;

use Modules\People\Entities\Person;

use Carbon\Carbon;

class PeopleReportingController extends BaseReportingController
{
    /**
     * Index
     */
    function index()
    {
        return view('people::reporting.people', [
            'people' => [[
                'Registered' => Person::count(),
                'Registered today' => Person::whereDate('created_at', '=', Carbon::today())->count(),
            ]],
            'nationalities' => Person::getNationalities(),
            'gender' => Person::getGenderDistribution(),
            'ageDistribution' => Person::getAgeDistribution(),
		]);
    }

    /**
     * Nationalities
     */
    function nationalities()
    {
        return response()->json([
            'labels' => null,
            'datasets' => collect(Person::getNationalities())
                ->map(function($e){ return [$e]; })
                ->toArray(),
        ]);
    }

    /**
     * Gender distribution
     */
    function genderDistribution()
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
    function ageDistribution()
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
    function registrationsPerDay()
    {
        $data = Person::getRegistrationsPerDay(60);
        return response()->json([
            'labels' => array_keys($data),
            'datasets' => [
                'Registrations' => array_values($data),
            ]
        ]);
    }

}
