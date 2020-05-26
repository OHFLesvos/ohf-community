<?php

namespace App\Http\Controllers\Fundraising\API;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\ValidatesDateRanges;
use App\Models\Fundraising\Donation;
use App\Models\Fundraising\Donor;
use App\Support\ChartResponseBuilder;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    use ValidatesDateRanges;

    /**
     * Display the amount of donors.
     *
     * @return \Illuminate\Http\Response
     */
    public function count(Request $request)
    {
        $this->authorize('view-fundraising-reports');

        $request->validate([
            'date' => [
                'nullable',
                'date',
            ],
        ]);
        $date = $request->input('date');

        return response()->json([
            'total' => Donor::createdUntil($date)->count(),
            'persons' => Donor::createdUntil($date)->whereNull('company')->count(),
            'companies' => Donor::createdUntil($date)->whereNotNull('company')->count(),
            'with_address' => Donor::createdUntil($date)->whereNotNull('city')->count(),
            'with_email' => Donor::createdUntil($date)->whereNotNull('email')->count(),
            'with_phone' => Donor::createdUntil($date)->whereNotNull('phone')->count(),
            'first' =>Donor::orderBy('created_at', 'asc')
                ->value('created_at'),
            'last' => Donor::orderBy('created_at', 'desc')
                ->value('created_at'),
        ]);
    }

    /**
     * Display all languages of donors.
     *
     * @return \Illuminate\Http\Response
     */
    public function languages(Request $request)
    {
        $this->authorize('view-fundraising-reports');

        $request->validate([
            'date' => [
                'nullable',
                'date',
            ],
        ]);
        $date = $request->input('date');

        return Donor::languageDistribution($date);
    }

    /**
     * Display all countries of donors.
     *
     * @return \Illuminate\Http\Response
     */
    public function countries(Request $request)
    {
        $this->authorize('view-fundraising-reports');

        $request->validate([
            'date' => [
                'nullable',
                'date',
            ],
        ]);
        $date = $request->input('date');

        return Donor::countryDistribution($date);
    }

    /**
     * Gets the number of registration per time unit.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function donorRegistrations(Request $request)
    {
        $this->authorize('view-fundraising-reports');

        $this->validateDateGranularity($request);
        [$dateFrom, $dateTo] = $this->getDatePeriodFromRequest($request);

        $registrations = Donor::inDateRange($dateFrom, $dateTo)
            ->groupByDateGranularity($request->input('granularity'))
            ->selectRaw('COUNT(*) AS `aggregated_value`')
            ->get()
            ->pluck('aggregated_value', 'date_label');

        return (new ChartResponseBuilder())
            ->dataset(__('app.registrations'), $registrations)
            ->build();
    }

    /**
     * Gets the number of registrations per time unit.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function donationRegistrations(Request $request)
    {
        $this->authorize('view-fundraising-reports');

        $this->validateDateGranularity($request);
        [$dateFrom, $dateTo] = $this->getDatePeriodFromRequest($request);

        $registrations = Donation::inDateRange($dateFrom, $dateTo, 'date')
            ->groupByDateGranularity($request->input('granularity'), 'date')
            ->selectRaw('COUNT(*) AS `aggregated_value`')
            ->get()
            ->pluck('aggregated_value', 'date_label');

        $amounts = Donation::inDateRange($dateFrom, $dateTo, 'date')
            ->groupByDateGranularity($request->input('granularity'), 'date')
            ->selectRaw('SUM(exchange_amount) AS `aggregated_value`')
            ->get()
            ->pluck('aggregated_value', 'date_label')
            ->map(fn ($e) => floatval($e));

        return (new ChartResponseBuilder())
            ->dataset(__('fundraising.donations'), $registrations)
            ->dataset(__('fundraising.donation_amount') . ' (' . config('fundraising.base_currency').')', $amounts, __('app.amount'))
            ->build();
    }

    /**
     * Display all currencies of donations.
     *
     * @return \Illuminate\Http\Response
     */
    public function currencies(Request $request)
    {
        $this->authorize('view-fundraising-reports');

        $request->validate([
            'date' => [
                'nullable',
                'date',
            ],
        ]);
        $date = $request->input('date');

        return Donation::currencyDistribution($date);
    }

    /**
     * Display all channels of donations.
     *
     * @return \Illuminate\Http\Response
     */
    public function channels(Request $request)
    {
        $this->authorize('view-fundraising-reports');

        $request->validate([
            'date' => [
                'nullable',
                'date',
            ],
        ]);
        $date = $request->input('date');

        return Donation::channelDistribution($date);
    }
}
