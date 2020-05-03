<?php

namespace App\Http\Controllers\Fundraising\API;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\ChartResponse;
use App\Http\Controllers\Traits\ValidatesDateRanges;
use App\Http\Resources\Fundraising\DonationCollection;
use App\Models\Fundraising\Donation;
use App\Models\Fundraising\Donor;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use MrCage\EzvExchangeRates\EzvExchangeRates;

class DonationController extends Controller
{
    use ValidatesDateRanges;
    use ChartResponse;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $this->authorize('list', Donation::class);

        $request->validate([
            'filter' => [
                'nullable',
            ],
            'page' => [
                'nullable',
                'integer',
                'min:1',
            ],
            'pageSize' => [
                'nullable',
                'integer',
                'min:1',
            ],
            'sortBy' => [
                'nullable',
                'alpha_dash',
                'filled',
                Rule::in([
                    'date',
                    'exchange_amount',
                    'in_name_of',
                    'created_at',
                ]),
            ],
            'sortDirection' => [
                'nullable',
                'in:asc,desc',
            ],
        ]);

        // Sorting, pagination and filter
        $sortBy = $request->input('sortBy', 'created_at');
        $sortDirection = $request->input('sortDirection', 'desc');
        $pageSize = $request->input('pageSize', 100);
        $filter = trim($request->input('filter', ''));

        $donations = Donation::query()
            ->forFilter($filter)
            ->orderBy($sortBy, $sortDirection)
            ->paginate($pageSize);
        return new DonationCollection($donations);
    }

    /**
     * Store donation and donor supplied by RaiseNow Webhook
     */
    public function raiseNowWebHookListener(Request $request) {
        // $data = $request->all();
        // Log::info("Donation webhook", $data);

        $request->validate([
            'stored_customer_firstname' => [
                'required',
            ],
            'stored_customer_lastname' => [
                'required',
            ],
            'stored_customer_street' => [
                'required',
            ],
            'stored_customer_city' => [
                'required',
            ],
            'amount' => [
                'required',
            ],
            'currency' => [
                'required',
            ],
            'payment_method' => [
                'required',
            ],
        ]);

        $street = $request->stored_customer_street . (! empty($request->stored_customer_street_number) ? ' ' . $request->stored_customer_street_number : '');
        $donor = Donor::where('first_name', $request->stored_customer_firstname)
            ->where('last_name', $request->stored_customer_lastname)
            ->where('street', $street)
            ->where('city', $request->stored_customer_city)
            ->first();
        if ($donor == null) {
            $donor = new Donor();
            $donor->salutation = $request->stored_customer_salutation;
            $donor->first_name = $request->stored_customer_firstname;
            $donor->last_name = $request->stored_customer_lastname;
            $donor->company = $request->stored_customer_company;
            $donor->street = $street;
            $donor->zip = $request->stored_customer_zip_code;
            $donor->city = $request->stored_customer_city;
            $donor->country_code = $request->stored_customer_country;
            $donor->email = $request->stored_customer_email;
            $donor->phone = $request->stored_customer_phone;
            $donor->language_code = $request->language;
            $donor->save();
        }

        $date = new Carbon($request->created);
        $amount = $request->amount / 100;
        $currency = strtoupper($request->currency);
        if ($currency != config('fundraising.base_currency')) {
            $exchange_rate = EzvExchangeRates::getExchangeRate($currency, $date);
            $exchange_amount = $amount * $exchange_rate;
        } else {
            $exchange_amount = $amount;
        }

        $payment_methods = [
            'ECA' => 'MasterCard',
            'MPW' => 'MasterPass',
            'PP' => 'PayPal',
            'VIS' => 'Visa',
            'TWI' => 'Twint',
            'PFC' => 'PostFinance Card',
            'PEF' => 'PostFinance E-Finance',
            'AMX' => 'American Express',
        ];
        $payment_method = $payment_methods[$request->payment_method] ?? $request->payment_method;

        $donation = new Donation();
        $donation->date = $date->toDateString();
        $donation->amount = $amount;
        $donation->currency = $currency;
        $donation->exchange_amount = $exchange_amount;
        $donation->channel = 'RaiseNow (' . $payment_method . ')';
        $donation->purpose = $request->stored_customer_message; // TODO consider stored_customer_purpose
        $donation->reference = $request->epp_transaction_id;

        $donor->addDonation($donation);
    }

    /**
     * Gets the number of registration per time unit.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function registrations(Request $request)
    {
        $this->authorize('list', Donation::class);

        $this->validateDateGranularity($request);
        [$dateFrom, $dateTo] = $this->getDatePeriodFromRequest($request);

        $registrations = Donation::inDateRange($dateFrom, $dateTo, 'date')
            ->groupByDateGranularity($request->input('granularity'), 'date')
            ->selectRaw('COUNT(*) AS `aggregated_value`')
            ->get()
            ->pluck('aggregated_value', 'date_label');

        return $this->simpleChartResponse(__('fundraising.donations'), $registrations);
    }

    /**
     * Gets the number of registration per time unit.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function amounts(Request $request)
    {
        $this->authorize('list', Donation::class);

        $this->validateDateGranularity($request);
        [$dateFrom, $dateTo] = $this->getDatePeriodFromRequest($request);

        $amounts = Donation::inDateRange($dateFrom, $dateTo, 'date')
            ->groupByDateGranularity($request->input('granularity'), 'date')
            ->selectRaw('SUM(exchange_amount) AS `aggregated_value`')
            ->get()
            ->pluck('aggregated_value', 'date_label');

        return $this->simpleChartResponse(__('fundraising.donation_amount'), $amounts);
    }
}
