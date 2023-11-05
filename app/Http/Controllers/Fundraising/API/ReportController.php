<?php

namespace App\Http\Controllers\Fundraising\API;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\ValidatesDateRanges;
use App\Http\Resources\Fundraising\Donation as DonationResource;
use App\Http\Resources\Fundraising\Donor as DonorResource;
use App\Models\Fundraising\Donation;
use App\Models\Fundraising\Donor;
use App\Support\ChartResponseBuilder;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    use ValidatesDateRanges;

    public function summary(): JsonResponse
    {
        $lastRegisteredDonor = Donor::query()->orderBy('created_at', 'desc')->first();
        $lastRegisteredDonation = Donation::query()->orderBy('created_at', 'desc')->with('donor')->first();

        return response()->json([
            'num_donors' => Donor::count(),
            'num_new_donors_month' => Donor::whereDate('created_at', '>', Carbon::now()->startOfMonth())->count(),
            'num_new_donors_year' => Donor::whereDate('created_at', '>', Carbon::now()->startOfYear())->count(),
            'last_registered_donor' => $lastRegisteredDonor != null ? new DonorResource($lastRegisteredDonor) : null,
            'last_registered_donation' => $lastRegisteredDonation != null ? new DonationResource($lastRegisteredDonation) : null,
            'num_donations_month' => Donation::whereDate('date', '>', Carbon::now()->startOfMonth())->count(),
            'num_donations_year' => Donation::whereDate('date', '>', Carbon::now()->startOfYear())->count(),
            'num_donations_total' => Donation::count(),
        ]);
    }

    public function count(Request $request): JsonResponse
    {
        $this->authorize('view-fundraising-reports');

        $request->validate([
            'date' => [
                'nullable',
                'date',
            ],
        ]);
        $date = $request->input('date');

        return response()
            ->json([
                'total' => Donor::createdUntil($date)->count(),
                'persons' => Donor::createdUntil($date)->whereNull('company')->count(),
                'companies' => Donor::createdUntil($date)->whereNotNull('company')->count(),
                'with_address' => Donor::createdUntil($date)->whereNotNull('city')->count(),
                'with_email' => Donor::createdUntil($date)->whereNotNull('email')->count(),
                'with_phone' => Donor::createdUntil($date)->whereNotNull('phone')->count(),
                'first' => Donor::orderBy('created_at', 'asc')
                    ->value('created_at'),
                'last' => Donor::orderBy('created_at', 'desc')
                    ->value('created_at'),
            ]);
    }

    public function languages(Request $request): JsonResponse
    {
        $this->authorize('view-fundraising-reports');

        $request->validate([
            'date' => [
                'nullable',
                'date',
            ],
        ]);
        $date = $request->input('date');

        return response()
            ->json(Donor::languageDistribution($date));
    }

    public function countries(Request $request): JsonResponse
    {
        $this->authorize('view-fundraising-reports');

        $request->validate([
            'date' => [
                'nullable',
                'date',
            ],
        ]);
        $date = $request->input('date');

        return response()
            ->json(Donor::countryDistribution($date));
    }

    /**
     * Gets the number of donor registration per time unit.
     */
    public function donorRegistrations(Request $request): JsonResponse
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
            ->dataset(__('Registrations'), $registrations)
            ->build();
    }

    /**
     * Gets the number of donation registrations per time unit.
     */
    public function donationRegistrations(Request $request): JsonResponse
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
            ->dataset(__('Donations'), $registrations)
            ->dataset(__('Donation amount').' ('.config('fundraising.base_currency').')', $amounts, __('Amount'))
            ->build();
    }

    /**
     * Display all currencies of donations.
     */
    public function currencies(Request $request): JsonResponse
    {
        $this->authorize('view-fundraising-reports');

        $request->validate([
            'date' => [
                'nullable',
                'date',
            ],
        ]);
        $date = $request->input('date');

        return response()
            ->json(Donation::currencyDistribution($date));
    }

    /**
     * Display all channels of donations.
     */
    public function channels(Request $request): JsonResponse
    {
        $this->authorize('view-fundraising-reports');

        $request->validate([
            'date' => [
                'nullable',
                'date',
            ],
        ]);
        $date = $request->input('date');

        return response()
            ->json(Donation::channelDistribution($date));
    }
}
