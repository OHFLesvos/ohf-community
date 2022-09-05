<?php

namespace App\Http\Controllers\Reporting;

use App\Http\Controllers\Controller;
use Carbon\Carbon;

abstract class BaseReportingController extends Controller
{
    protected static function createDateCollectionEmpty($from, $to)
    {
        $dates = [];
        $date = $to->clone();
        do {
            $dates[$date->toDateString()] = null;
        } while ($date->subDay()->gt($from));

        return collect($dates);
    }

    protected static function createWeekCollectionEmpty($from, $to)
    {
        $dates = [];
        $date = $to->clone();
        do {
            // PHP date format: http://php.net/manual/en/function.date.php
            $dates[$date->format('W / Y')] = null;
        } while ($date->subWeek()->gt($from));

        return collect($dates);
    }

    protected static function createMonthCollectionEmpty($from, $to)
    {
        $dates = [];
        $date = $to->clone();
        do {
            // PHP date format: http://php.net/manual/en/function.date.php
            $dates[$date->format('F Y')] = null;
        } while ($date->subMonth()->gt($from));

        return collect($dates);
    }

    protected static function createYearCollectionEmpty($from, $to)
    {
        $dates = [];
        $date = $to->clone();
        do {
            $dates['Year '.$date->year] = null;
        } while ($date->subYear()->gt($from));

        return collect($dates);
    }

    protected static function createDayOfWeekCollectionEmpty()
    {
        $dates = [];
        $date = Carbon::now()->startOfWeek();
        do {
            // PHP date format: http://php.net/manual/en/function.date.php
            $dates[$date->format('l')] = null;
        } while ($date->addDay()->lte(Carbon::now()->endOfWeek()));

        return collect($dates);
    }
}
