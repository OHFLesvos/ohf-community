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
    protected static function getDatePeriodFromRequest(Request $request, ?int $defaultDays = 30, string $dateStartField = 'from', string $dateEndField = 'to'): array
    {
        // Validate request data
        $request->validate([
            $dateStartField => [
                'nullable',
                'date',
                'before_or_equal:'.$dateEndField,
            ],
            $dateEndField => [
                'nullable',
                'date',
                'after_or_equal:'.$dateStartField,
            ],
        ]);

        // Return as array (from, to)
        return [
            $request->filled($dateStartField)
                ? new Carbon($request->input($dateStartField))
                : Carbon::today()->subDays($defaultDays),
            $request->filled($dateEndField)
                ? new Carbon($request->input($dateEndField))
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
