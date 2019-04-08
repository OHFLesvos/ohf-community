<?php

namespace App\Http\Controllers\Reporting;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

use Carbon\Carbon;

abstract class BaseReportingController extends Controller
{
    protected static function createDateCollectionEmpty($from, $to) {
        $dates = [];
        $date = (clone $to);
        do {
            $dates[$date->toDateString()] = null;
        } while ($date->subDay()->gt($from));
        return collect($dates);
    }

    protected static function createWeekCollectionEmpty($from, $to) {
        $dates = [];
        $date = (clone $to);
        do {
            // PHP date format: http://php.net/manual/en/function.date.php
            $dates[$date->format('W / Y')] = null;
        } while ($date->subWeek()->gt($from));
        return collect($dates);
    }

    protected static function createMonthCollectionEmpty($from, $to) {
        $dates = [];
        $date = (clone $to);
        do {
            // PHP date format: http://php.net/manual/en/function.date.php
            $dates[$date->format('F Y')] = null;
        } while ($date->subMonth()->gt($from));
        return collect($dates);
    }

    protected static function createYearCollectionEmpty($from, $to) {
        $dates = [];
        $date = (clone $to);
        do {
            $dates["Year " . $date->year] = null;
        } while ($date->subYear()->gt($from));
        return collect($dates);
    }

    protected static function createDayOfWeekCollectionEmpty() {
        $dates = [];
        $date = Carbon::now()->startOfWeek();
        do {
            // PHP date format: http://php.net/manual/en/function.date.php
            $dates[$date->format('l')] = null;
        } while ($date->addDay()->lte(Carbon::now()->endOfWeek()));
        return collect($dates);
    }

    /**
     * Parses optional date boundaries from a request
     */
    protected static function getDatesFromRequest(Request $request) {
        // Validate request data
        Validator::make($request->all(), [
            'from' => 'date',
            'to' => 'date',
        ])->validate();

        // Parse dates from request
        $from = isset($request->from) ? new Carbon($request->from) : Carbon::today()->subDays(30);
        $to = isset($request->to) ? new Carbon($request->to) : Carbon::today();

        // Return as array
        return [$from, $to];
    }

    /**
     * Parses optional date boundaries from a request
     */
    protected static function getMonthRangeDatesFromRequest(Request $request) {
        // Validate request data
        Validator::make($request->all(), [
            'year' => 'numeric|required_with:month',
            'month' => 'numeric|min:1|max:12|required_with:year',
        ])->validate();

        // Parse dates from request
        $now = Carbon::now();
        $year = $request->year != null ? $request->year : $now->year;
        $month = $request->month != null ? $request->month : $now->month;

        $from = Carbon::createFromDate($year, $month, 1)->startOfMonth();
        $to = (clone $from)->endOfMonth();

        // Return as array
        return [$from, $to];
    }
}
