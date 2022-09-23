<?php

namespace App\Http\Controllers\Traits;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

trait ValidatesDateRanges
{
    /**
     * Parses optional date boundaries (year-month-day) from a request
     *
     * @return Carbon[]
     */
    protected static function getDatePeriodFromRequest(Request $request, ?int $defaultDays = 30): array
    {
        // Validate request data
        $request->validate([
            'from' => [
                'nullable',
                'date',
                'before_or_equal:to',
            ],
            'to' => [
                'nullable',
                'date',
                'after_or_equal:from',
            ],
        ]);

        // Return as array (from, to)
        return [
            $request->filled('from')
                ? new Carbon($request->input('from'))
                : Carbon::today()->subDays($defaultDays),
            $request->filled('to')
                ? new Carbon($request->input('to'))
                : Carbon::today(),
        ];
    }

    protected function validateDateGranularity(Request $request): void
    {
        $request->validate([
            'granularity' => [
                'nullable',
                Rule::in(['years', 'months', 'weeks', 'days']),
            ],
        ]);
    }
}
