<?php

namespace App\Http\Controllers\Reporting;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class MonthlySummaryReportingController extends BaseReportingController
{
    public function index(Request $request) {
        return view('reporting.monthly-summary', [

        ]);
    }
}
