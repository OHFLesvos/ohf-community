<?php

namespace App\Http\Controllers\People\Reporting;

use App\Http\Controllers\Reporting\BaseReportingController;
use App\Http\Controllers\Traits\ValidatesDateRanges;
use Illuminate\Http\Request;

class PeopleReportingController extends BaseReportingController
{
    use ValidatesDateRanges;

    /**
     * Index
     */
    public function index(Request $request)
    {
        [$dateFrom, $dateTo] = self::getDatePeriodFromRequest($request, 30);

        return view('people.reporting.people', [
            'dateFrom' => $dateFrom,
            'dateTo' => $dateTo,
        ]);
    }
}
