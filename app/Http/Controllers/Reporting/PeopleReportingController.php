<?php

namespace App\Http\Controllers\Reporting;

use App\Person;
use App\Transaction;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class PeopleReportingController extends Controller
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

    /**
     * Index
     */
    function index() {
        return view('reporting.people', [
            'nationalities' => self::getNationalities(),
            'gender' => self::getGenderDistribution(),
            'demographics' => self::getDemographics(),
            'numberTypes' => self::getNumberTypes(),
		]);
    }

    /**
     * Nationalities
     */
    function nationalities() {
        return response()->json([
            'labels' => null,
            'datasets' => collect(self::getNationalities())
                ->map(function($e){ return [$e]; })
                ->toArray(),
        ]);
    }

    private static function getNationalities($limit = 6) {
        $nationalities = collect(
            Person::select('nationality', \DB::raw('count(*) as total'))
                    ->groupBy('nationality')
                    ->whereNotNull('nationality')
                    ->orderBy('total', 'DESC')
                    ->get()
            )->mapWithKeys(function($i){
                return [$i['nationality'] =>  $i['total']];
            });
        $data = $nationalities->slice(0, $limit)->toArray();
        $data['Other'] = $nationalities->slice($limit)->reduce(function ($carry, $item) {
                 return $carry + $item;
            });
        return $data;
    }

    /**
     * Gender distribution
     */
    function genderDistribution() {
        return response()->json([
            'labels' => null,
            'datasets' => collect(self::getGenderDistribution())
                ->map(function($e){ return [$e]; })
                ->toArray(),
        ]);
    }

    private static function getGenderDistribution() {
        return collect(
            Person::select('gender', \DB::raw('count(*) as total'))
                    ->groupBy('gender')
                    ->whereNotNull('gender')
                    ->get()
            )->mapWithKeys(function($i){
                if ($i['gender'] == 'm') {
                    $label = 'Male';
                } else if ($i['gender'] == 'f') {
                    $label = 'Female';
                } else {
                    $label = $i['gender'];
                }
                return [$label =>  $i['total']];
            })
            ->toArray();
    }

    /**
     * Demographics
     */
    function demographics() {
        return response()->json([
            'labels' => null,
            'datasets' => collect(self::getDemographics())
                ->map(function($e){ return [$e]; })
                ->toArray(),
        ]);
    }

    private static function getDemographics() {
        return [
            'Toddlers (0-4)' => Person::whereNotNull('date_of_birth')
                ->whereDate('date_of_birth', '>', Carbon::now()->subYears(5))
                ->select('date_of_birth')
                ->count(),
            'Children (5-11)' => Person::whereNotNull('date_of_birth')
                ->whereDate('date_of_birth', '>', Carbon::now()->subYears(12))
                ->whereDate('date_of_birth', '<=', Carbon::now()->subYears(5))
                ->select('date_of_birth')
                ->count(),
            'Adolescents (12-17)' => Person::whereNotNull('date_of_birth')
                ->whereDate('date_of_birth', '>', Carbon::now()->subYears(18))
                ->whereDate('date_of_birth', '<=', Carbon::now()->subYears(12))
                ->select('date_of_birth')
                ->count(),            
            'Adults (18+)' => Person::whereNotNull('date_of_birth')
                ->whereDate('date_of_birth', '<=', Carbon::now()->subYears(18))
                ->select('date_of_birth')
                ->count(),            
        ];
    }

    /**
     * Number types
     */
    function numberTypes() {
        return response()->json([
            'labels' => null,
            'datasets' => collect(self::getNumberTypes())
                ->map(function($e){ return [$e]; })
                ->toArray(),
        ]);
    }

    private static function getNumberTypes() {
        return [
            'Police Number (05/...)' => Person::whereNotNull('police_no')->count(),          
            'Case Number' => Person::whereNotNull('case_no')->count(),          
            'Medical Number' => Person::whereNotNull('medical_no')->count(),          
            'Registration Number' => Person::whereNotNull('registration_no')->count(),          
            'Section Card Number' => Person::whereNotNull('section_card_no')->count(),          
            'Temporary Number' => Person::whereNotNull('temp_no')->count(),          
        ];
    }

    /**
     * Visitors per day
     */
    function visitorsPerDay() {
        $from = Carbon::now()->subMonth(3);
        $to = Carbon::now();
        $data = self::getvisitorsPerDay($from, $to);
        return response()->json([
            'labels' => array_keys($data),
            'datasets' => [
                'Visitors' => array_values($data),
            ]
        ]);
    }

    private static function getvisitorsPerDay($from, $to) {
        return self::createDateCollectionEmpty($from, $to)
            ->merge(self::getVisitorsPerDayQuery($from, $to)
                ->get()
                ->mapWithKeys(function ($item) {
                    return [$item->date => $item->visitors];
                }))
            ->reverse()
            ->toArray();
    }

    private static function createDateCollectionEmpty($from, $to) {
        $dates = [];
        $date = (clone $to);
        do {
            $dates[$date->toDateString()] = null;
        } while ($date->subDay()->gt($from));
        return collect($dates);
    }

    /**
     * Visitors per week
     */
    function visitorsPerWeek() {
        $from = Carbon::now()->subMonth(6)->startOfWeek();
        $to = Carbon::now();
        $data = self::getvisitorsPerWeek($from, $to);
        return response()->json([
            'labels' => array_keys($data),
            'datasets' => [
                'Visitors' => array_values($data),
            ]
        ]);
    }

    private static function getvisitorsPerWeek($from, $to) {
        $visitsPerDayQuery = self::getVisitorsPerDayQuery($from, $to);
        return self::createWeekCollectionEmpty($from, $to)
            ->merge(
                // MySQL week number formats: https://www.w3resource.com/mysql/date-and-time-functions/mysql-week-function.php
                DB::table(DB::raw('('.$visitsPerDayQuery->toSql().') as o2'))
                    ->select(DB::raw('CONCAT(LPAD(WEEK(date, 3), 2, 0), \' / \', YEAR(date)) as week'), DB::raw('SUM(visitors) as visitors'))
                    ->groupBy(DB::raw('WEEK(date, 3)'), DB::raw('YEAR(date)'))
                    ->orderBy('date', 'DESC')
                    ->mergeBindings($visitsPerDayQuery)
                    ->get()
                    ->mapWithKeys(function ($item) {
                        return [$item->week => $item->visitors];
                    })
            )
            ->reverse()
            ->toArray();
    }

    private static function createWeekCollectionEmpty($from, $to) {
        $dates = [];
        $date = (clone $to);
        do {
            // PHP date format: http://php.net/manual/en/function.date.php
            $dates[$date->format('W / Y')] = null;
        } while ($date->subWeek()->gt($from));
        return collect($dates);
    }

    /**
     * Visitors per month
     */
    function visitorsPerMonth() {
        $from = Carbon::now()->subMonth(12)->startOfMonth();
        $to = Carbon::now();
        $data = self::getvisitorsPerMonth($from, $to);
        return response()->json([
            'labels' => array_keys($data),
            'datasets' => [
                'Visitors' => array_values($data),
            ]
        ]);
    }

    private static function getvisitorsPerMonth($from, $to) {
        $visitsPerDayQuery = self::getVisitorsPerDayQuery($from, $to);
        return self::createMonthCollectionEmpty($from, $to)
            ->merge(
                DB::table(DB::raw('('.$visitsPerDayQuery->toSql().') as o2'))
                    ->select(DB::raw('DATE_FORMAT(date, \'%M %Y\') as month'), DB::raw('SUM(visitors) as visitors')) // CONCAT(MONTH(date), \'/\', YEAR(date))
                    ->groupBy(DB::raw('YEAR(date)'), DB::raw('MONTH(date)'))
                    ->orderBy('date', 'DESC')
                    ->mergeBindings($visitsPerDayQuery)
                    ->get()
                    ->mapWithKeys(function ($item) {
                        return [$item->month => $item->visitors];
                    })
            )
            ->reverse()
            ->toArray();
    }

    private static function createMonthCollectionEmpty($from, $to) {
        $dates = [];
        $date = (clone $to);
        do {
            // PHP date format: http://php.net/manual/en/function.date.php
            $dates[$date->format('F Y')] = null;
        } while ($date->subMonth()->gt($from));
        return collect($dates);
    }

    /**
     * Visitors per year
     */
    function visitorsPerYear() {
        $from = Carbon::now()->subYear(2)->startOfYear();
        $to = Carbon::now();
        $data = self::getvisitorsPerYear($from, $to);
        return response()->json([
            'labels' => array_keys($data),
            'datasets' => [
                'Visitors' => array_values($data),
            ]
        ]);
    }

    private static function getvisitorsPerYear($from, $to) {
        $visitsPerDayQuery = self::getVisitorsPerDayQuery($from, $to);
        return self::createYearCollectionEmpty($from, $to)
            ->merge(
                DB::table(DB::raw('('.$visitsPerDayQuery->toSql().') as o2'))
                    ->select(DB::raw('YEAR(date) as year'), DB::raw('SUM(visitors) as visitors'))
                    ->groupBy(DB::raw('YEAR(date)'))
                    ->orderBy('date', 'DESC')
                    ->mergeBindings($visitsPerDayQuery)
                    ->get()
                    ->mapWithKeys(function ($item) {
                        return ["Year " . $item->year => (int)$item->visitors];
                    })
            )
            ->reverse()
            ->toArray();
    }

    private static function createYearCollectionEmpty($from, $to) {
        $dates = [];
        $date = (clone $to);
        do {
            $dates["Year " . $date->year] = null;
        } while ($date->subYear()->gt($from));
        return collect($dates);
    }

    /**
     * Average visitors per day of week
     */
    function avgVisitorsPerDayOfWeek() {
        $from = Carbon::now()->subMonth(3)->startOfWeek();
        $to = Carbon::now();
        $data = self::getVisitorsPerDayOfWeek($from, $to);
        return response()->json([
            'labels' => array_keys($data),
            'datasets' => [
                'Visitors' => array_values($data),
            ]
        ]);
    }

    private static function getVisitorsPerDayOfWeek($from, $to) {
        $visitsPerDayQuery = self::getVisitorsPerDayQuery($from, $to);
        return self::createDayOfWeekCollectionEmpty()
            ->merge(
                // MySQL day name and day of week formats: 
                //    https://www.w3resource.com/mysql/date-and-time-functions/mysql-dayname-function.php
                //    https://www.w3resource.com/mysql/date-and-time-functions/mysql-dayofweek-function.php
                DB::table(DB::raw('('.$visitsPerDayQuery->toSql().') as o2'))
                    ->select(DB::raw('DAYNAME(date) as day'), DB::raw('AVG(visitors) as visitors'))
                    ->groupBy(DB::raw('DAYOFWEEK(date)'))
                    ->orderBy(DB::raw('DAYOFWEEK(date)'))
                    ->mergeBindings($visitsPerDayQuery)
                    ->get()
                    ->mapWithKeys(function ($item) {
                        return [$item->day => round($item->visitors, 1)];
                    })
            )
            ->toArray();
    }

    private static function createDayOfWeekCollectionEmpty() {
        $dates = [];
        $date = Carbon::now()->startOfWeek();
        do {
            // PHP date format: http://php.net/manual/en/function.date.php
            $dates[$date->format('l')] = null;
        } while ($date->addDay()->lte(Carbon::now()->endOfWeek()));
        return collect($dates);
    }
    

    private static function getVisitorsPerDayQuery($from, $to) {
        $personsQuery = DB::table('transactions')
            ->select(DB::raw('transactionable_id AS person_id'), DB::raw('DATE(created_at) AS date'), 'transactionable_type')
            ->groupBy(DB::raw('DAY(created_at)'), DB::raw('MONTH(created_at)'), DB::raw('YEAR(created_at)'), 'transactionable_id')
            ->having('transactionable_type', 'App\Person')
            ->whereDate('created_at', '>=', $from)
            ->whereDate('created_at', '<=', $to);

        return DB::table(DB::raw('('.$personsQuery->toSql().') as o1'))
            ->select('date', DB::raw('COUNT(`person_id`) as visitors'))
            ->groupBy('date')
            ->mergeBindings($personsQuery);
    }

    /**
     * Registrations per day
     */
    function registrationsPerDay() {
        $data = self::getRegistrationsPerDay(91);
        return response()->json([
            'labels' => array_keys($data),
            'datasets' => [
                'Registrations' => array_values($data),
            ]
        ]);
    }

    private static function getRegistrationsPerDay($numDays) {
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
