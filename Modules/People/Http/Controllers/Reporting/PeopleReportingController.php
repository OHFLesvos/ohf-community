<?php

namespace Modules\People\Http\Controllers\Reporting;

use App\Http\Controllers\Reporting\BaseReportingController;

use Modules\People\Entities\Person;

use Illuminate\Support\Facades\DB;

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
            'nationalities' => self::getNationalities(),
            'gender' => self::getGenderDistribution(),
            'ageDistribution' => self::getAgeDistribution(),
		]);
    }

    /**
     * Nationalities
     */
    function nationalities()
    {
        return response()->json([
            'labels' => null,
            'datasets' => collect(self::getNationalities())
                ->map(function($e){ return [$e]; })
                ->toArray(),
        ]);
    }

    private static function getNationalities()
    {
        return collect(
            Person::select('nationality', \DB::raw('count(*) as total'))
                    ->groupBy('nationality')
                    ->whereNotNull('nationality')
                    ->orderBy('total', 'DESC')
                    ->get()
            )
            ->pluck('total', 'nationality')
            ->toArray();
    }

    /**
     * Gender distribution
     */
    function genderDistribution()
    {
        $gender = self::getGenderDistribution();
        return response()->json([
            'labels' => array_keys($gender),
            'datasets' => [array_values($gender)],
        ]);
    }

    private static function getGenderDistribution()
    {
        return collect(
            Person::select('gender', \DB::raw('count(*) as total'))
                    ->groupBy('gender')
                    ->whereNotNull('gender')
                    ->get()
            )->mapWithKeys(function($i){
                if ($i['gender'] == 'm') {
                    $label = __('app.male');
                } else if ($i['gender'] == 'f') {
                    $label = __('app.female');
                } else {
                    $label = $i['gender'];
                }
                return [$label =>  $i['total']];
            })
            ->toArray();
    }

    /**
     * Age distribution
     */
    function ageDistribution()
    {
        $data = self::getAgeDistribution();
        return response()->json([
            'labels' => array_keys($data),
            'datasets' => [array_values($data)],
        ]);
    }

    private static function getAgeDistribution()
    {
        return [
            '0 - 4' => Person::whereNotNull('date_of_birth')
                ->whereDate('date_of_birth', '>', Carbon::now()->subYears(5))
                ->select('date_of_birth')
                ->count(),
            '5 - 11' => Person::whereNotNull('date_of_birth')
                ->whereDate('date_of_birth', '>', Carbon::now()->subYears(12))
                ->whereDate('date_of_birth', '<=', Carbon::now()->subYears(5))
                ->select('date_of_birth')
                ->count(),
            '12 - 17' => Person::whereNotNull('date_of_birth')
                ->whereDate('date_of_birth', '>', Carbon::now()->subYears(18))
                ->whereDate('date_of_birth', '<=', Carbon::now()->subYears(12))
                ->select('date_of_birth')
                ->count(),
            '18 - 24' => Person::whereNotNull('date_of_birth')
                ->whereDate('date_of_birth', '>', Carbon::now()->subYears(25))
                ->whereDate('date_of_birth', '<=', Carbon::now()->subYears(18))
                ->select('date_of_birth')
                ->count(),
            '25 - 34' => Person::whereNotNull('date_of_birth')
                ->whereDate('date_of_birth', '>', Carbon::now()->subYears(35))
                ->whereDate('date_of_birth', '<=', Carbon::now()->subYears(25))
                ->select('date_of_birth')
                ->count(),
            '35 - 44' => Person::whereNotNull('date_of_birth')
                ->whereDate('date_of_birth', '>', Carbon::now()->subYears(45))
                ->whereDate('date_of_birth', '<=', Carbon::now()->subYears(35))
                ->select('date_of_birth')
                ->count(),
            '45 - 54' => Person::whereNotNull('date_of_birth')
                ->whereDate('date_of_birth', '>', Carbon::now()->subYears(55))
                ->whereDate('date_of_birth', '<=', Carbon::now()->subYears(45))
                ->select('date_of_birth')
                ->count(),
            '55 - 64' => Person::whereNotNull('date_of_birth')
                ->whereDate('date_of_birth', '>', Carbon::now()->subYears(65))
                ->whereDate('date_of_birth', '<=', Carbon::now()->subYears(55))
                ->select('date_of_birth')
                ->count(),
            '65 - 74' => Person::whereNotNull('date_of_birth')
                ->whereDate('date_of_birth', '>', Carbon::now()->subYears(75))
                ->whereDate('date_of_birth', '<=', Carbon::now()->subYears(65))
                ->select('date_of_birth')
                ->count(),
            '75+' => Person::whereNotNull('date_of_birth')
                ->whereDate('date_of_birth', '<=', Carbon::now()->subYears(75))
                ->select('date_of_birth')
                ->count(),            
        ];
    }

    /**
     * Registrations per day
     */
    function registrationsPerDay()
    {
        $data = self::getRegistrationsPerDay(60);
        return response()->json([
            'labels' => array_keys($data),
            'datasets' => [
                'Registrations' => array_values($data),
            ]
        ]);
    }

    private static function getRegistrationsPerDay($numDays)
    {
		$registrations = Person::where('created_at', '>=', Carbon::now()->subDays($numDays))
            ->withTrashed()
			->groupBy('date')
			->orderBy('date', 'DESC')
			->get(array(
				\DB::raw('Date(created_at) as date'),
				\DB::raw('COUNT(*) as "count"')
			))
			->mapWithKeys(function ($item) {
				return [$item['date'] => $item['count']];
			})
			->reverse()
			->all();
		for ($i=1; $i < $numDays; $i++) {
			$dateKey = Carbon::now()->subDays($i)->toDateString();
			if (!isset($registrations[$dateKey])) {
				$registrations[$dateKey] = 0;
			}
		}
		ksort($registrations);
		return $registrations;
	}
}
