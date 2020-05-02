<?php

namespace App\Util;

use Carbon\Carbon;
use Illuminate\Support\Collection;

class DateRangeUtil
{
    public static function createDateCollection(Carbon $from, Carbon $to, ?string $type = 'days'): Collection
    {
        switch ($type) {
            case 'years':
                return self::createYearCollection($from, $to);
            case 'months':
                return self::createMonthCollection($from, $to);
            case 'weeks':
                return self::createWeekCollection($from, $to);
            default: // days
                return self::createDayCollection($from, $to);
        }
    }

    public static function createYearCollection(Carbon $from, Carbon $to): Collection
    {
        $dates = collect([]);
        $date = $from->clone();
        do {
            $dates->push($date->isoFormat('YYYY'));
        } while ($date->addYear()->lte($to));
        return $dates;
    }

    public static function createMonthCollection(Carbon $from, Carbon $to): Collection
    {
        $dates = collect([]);
        $date = $from->clone();
        do {
            $dates->push($date->isoFormat('YYYY-MM'));
        } while ($date->addMonth()->lte($to));
        return $dates;
    }

    public static function createWeekCollection(Carbon $from, Carbon $to): Collection
    {
        $dates = collect([]);
        $date = $from->clone();
        do {
            $dates->push($date->isoFormat('GGGG-WW'));
        } while ($date->addWeek()->lte($to));
        return $dates;
    }

    public static function createDayCollection(Carbon $from, Carbon $to): Collection
    {
        $dates = collect([]);
        $date = $from->clone();
        do {
            $dates->push($date->isoFormat('YYYY-MM-DD'));
        } while ($date->addDay()->lte($to));
        return $dates;
    }
}
