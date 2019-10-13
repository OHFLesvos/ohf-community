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
        $date = $to->clone();
        do {
            $dates[$date->toDateString()] = null;
        } while ($date->subDay()->gt($from));
        return collect($dates);
    }

    protected static function createWeekCollectionEmpty($from, $to) {
        $dates = [];
        $date = $to->clone();
        do {
            // PHP date format: http://php.net/manual/en/function.date.php
            $dates[$date->format('W / Y')] = null;
        } while ($date->subWeek()->gt($from));
        return collect($dates);
    }

    protected static function createMonthCollectionEmpty($from, $to) {
        $dates = [];
        $date = $to->clone();
        do {
            // PHP date format: http://php.net/manual/en/function.date.php
            $dates[$date->format('F Y')] = null;
        } while ($date->subMonth()->gt($from));
        return collect($dates);
    }

    protected static function createYearCollectionEmpty($from, $to) {
        $dates = [];
        $date = $to->clone();
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
     * Parses optional date boundaries (year-month-day) from a request
     */
    protected static function getDatePeriodFromRequest(Request $request, $defaultDays = 30): array
    {
        // Validate request data
        $request->validate([
            'from' => [
                'nullable',
                'date',
                'before_or_equal:to'
            ],
            'to' => [
                'nullable',
                'date',
                'after_or_equal:from'
            ],
        ]);

        // Return as array (from, to)
        return [
            $request->filled('from') ? new Carbon($request->input('from')) : Carbon::today()->subDays($defaultDays),
            $request->filled('to') ? new Carbon($request->input('to')) : Carbon::today(),
        ];
    }

    /**
     * Parses optional date boundaries (year-month) from a request
     */
    protected static function getMonthRangeDatesFromRequest(Request $request): array
    {
        // Validate request data
        $request->validate([
            'year' => [
                'numeric',
                'required_with:month'
            ],
            'month' => [
                'numeric',
                'min:1',
                'max:12',
                'required_with:year'
            ],
        ]);

        // Parse dates from request
        $now = Carbon::now();
        $year = $request->input('year', $now->year);
        $month = $request->input('month', $now->month);
        $from = Carbon::createFromDate($year, $month, 1)->startOfMonth();

        // Return as array (from, to)
        return [
            $from,
            $from->clone()->endOfMonth(),
        ];
    }
}
