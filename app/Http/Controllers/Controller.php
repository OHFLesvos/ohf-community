<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;

use Carbon\Carbon;

class Controller extends BaseController
{
    use AuthorizesRequests {
        resourceAbilityMap as protected traitResourceAbilityMap;
    }
    use DispatchesJobs, ValidatesRequests;

    protected function resourceAbilityMap()
    {
        // Ensure method index is automatically authorized using policy method 'list' if authorizeResource() is used
        return array_merge($this->traitResourceAbilityMap(), ['index' => 'list']);
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
