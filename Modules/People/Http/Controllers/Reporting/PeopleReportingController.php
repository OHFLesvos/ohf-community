<?php

namespace Modules\People\Http\Controllers\Reporting;

use App\Person;
use App\Http\Controllers\Reporting\BaseReportingController;

use Modules\People\Entities\RevokedCard;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Config;

use Carbon\Carbon;

class PeopleReportingController extends BaseReportingController
{
    /**
     * Index
     */
    function index() {
        $daily = array_values(self::getVisitorsPerDay(Carbon::now()->subDay()->startOfDay(), Carbon::now()));
        $weekly = array_values(self::getVisitorsPerWeek(Carbon::now()->subWeek()->startOfWeek(), Carbon::now()));
        $monthly = array_values(self::getVisitorsPerMonth(Carbon::now()->subMonth()->startOfMonth(), Carbon::now()));
        $year = array_values(self::getVisitorsPerYear(Carbon::now()->subYear()->startOfYear(), Carbon::now()));
        return view('reporting.people', [
            'people' => [[
                'Registered' => Person::count(),
                'Registered today' => Person::whereDate('created_at', '=', Carbon::today())->count(),
            ]],
            'nationalities' => self::getNationalities(),
            'gender' => self::getGenderDistribution(),
            'demographics' => self::getDemographics(),
            'numberTypes' => self::getNumberTypes()->toArray(),
            'visitors' => [
                [
                    'Today' => $daily[1] ?? 0,
                    'This week' => $weekly[1] ?? 0,
                    'This month' => $monthly[1] ?? 0,
                    'This year' => $year[1] ?? 0,
                ], 
                [
                    'Yesterday' => $daily[0] ?? 0,
                    'Last week' => $weekly[0] ?? 0,
                    'Last month' => $monthly[0] ?? 0,
                    'Last year' => $year[0] ?? 0,
                ], 
                [
                    // TODO peak visitors per day
                    'Daily average' => round(self::getAvgVisitorsPerDay( Carbon::now()->subMonth(3)->startOfWeek(), Carbon::now())),
                    'Frequent' => self::getNumberOfFrequentVisitors(),
                    'Cards issued' => Person::whereNotNull('card_no')->count(),
                    'Cards revoked' => RevokedCard::count(),
                ]
            ],
            'top_names' => Person::select('name', DB::raw('COUNT(name) as count'))
                ->groupBy('name')
                ->orderBy('count', 'desc')
                ->limit(10)
                ->get(),
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

    private static function getNationalities($limit = 10) {
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
        $other = $nationalities->slice($limit)->reduce(function ($carry, $item) {
                 return $carry + $item;
            });
        if ($other > 0) {
            $data['Other'] = $other;
        }
        return $data;
    }

    /**
     * Gender distribution
     */
    function genderDistribution() {
        $gender = self::getGenderDistribution();
        return response()->json([
            'labels' => array_keys($gender),
            'datasets' => [array_values($gender)],
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
     * Demographics
     */
    function demographics() {
        $demographics = self::getDemographics();
        return response()->json([
            'labels' => array_keys($demographics),
            'datasets' => [array_values($demographics)],
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
            'datasets' => self::getNumberTypes()
                ->map(function($e){ return [$e]; })
                ->toArray()
        ]);
    }

    private static function getNumberTypes() {
        return collect([
            __('people::people.police_number') . ' (05/...)' => Person::whereNotNull('police_no')->count(),          
            __('people::people.case_number') => Person::whereNotNull('case_no_hash')->count(),          
            __('people::people.registration_number') => Person::whereNotNull('registration_no')->count(),          
            __('people::people.section_card_number') => Person::whereNotNull('section_card_no')->count(),          
        ])
        ->sort()
        ->reverse();
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

    /**
     * Visitors per month
     */
    function visitorsPerMonth() {
        $from = Carbon::now()->subMonth(12)->startOfMonth();
        $to = Carbon::now();
        $data = self::getVisitorsPerMonth($from, $to);
        return response()->json([
            'labels' => array_keys($data),
            'datasets' => [
                'Visitors' => array_values($data),
            ]
        ]);
    }

    private static function getVisitorsPerMonth($from, $to) {
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

    private static function getAvgVisitorsPerDay($from, $to) {
        $query = self::getVisitorsPerDayQuery($from, $to);
        return DB::table(DB::raw('('.$query->toSql().') as o2'))
            ->select(DB::raw('AVG(visitors) as avg'))
            ->mergeBindings($query)
            ->get()
            ->first()
            ->avg;
    }

    private static function getVisitorsPerDayQuery($from, $to) {
        $personsQuery = DB::table('coupon_handouts')
            ->select('person_id', 'date')
            ->groupBy('date', 'person_id')
            ->whereDate('date', '>=', $from)
            ->whereDate('date', '<=', $to);

        return DB::table(DB::raw('('.$personsQuery->toSql().') as o1'))
            ->select('date', DB::raw('COUNT(`person_id`) as visitors'))
            ->groupBy('date')
            ->mergeBindings($personsQuery);
    }

    /**
     * Number of frequent visitors
     */
    public static function getNumberOfFrequentVisitors() {
        $weeks = \Setting::get('bank.frequent_visitor_weeks', Config::get('bank.frequent_visitor_weeks'));
        $threshold = \Setting::get('bank.frequent_visitor_threshold', Config::get('bank.frequent_visitor_threshold'));

        $q1 = DB::table('coupon_handouts')
            ->select('person_id', 'date')
            ->groupBy('date', 'person_id')
            ->whereDate('date', '>=', Carbon::today()->subWeeks($weeks));

        $q2 = DB::table(DB::raw('('.$q1->toSql().') as o1'))
            ->select('person_id', DB::raw('COUNT(`person_id`) as visits'))
            ->groupBy('person_id')
            ->having('visits', '>=', $threshold)
            ->mergeBindings($q1);

        $q3 = DB::table(DB::raw('('.$q2->toSql().') as o2'))
            ->select(DB::raw('COUNT(`person_id`) as num'))
            ->mergeBindings($q2);

        return $q3->first()->num;
    }

    /**
     * Registrations per day
     */
    function registrationsPerDay() {
        $data = self::getRegistrationsPerDay(60);
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
