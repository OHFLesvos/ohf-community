<?php

namespace App\Http\Controllers\Fundraising\API;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\ValidatesDateRanges;
use App\Http\Requests\Fundraising\StoreDonation;
use App\Http\Resources\Fundraising\DonationCollection;
use App\Models\Fundraising\Donation;
use App\Models\Fundraising\Donor;
use App\Support\ChartResponseBuilder;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;
use MrCage\EzvExchangeRates\EzvExchangeRates;

class DonationController extends Controller
{
    use ValidatesDateRanges;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $this->authorize('viewAny', Donation::class);

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
            ->with('donor')
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
     * Stores a new donation.
     *
     * @param  \App\Http\Requests\Fundraising\StoreDonation  $request
     * @param  \App\Models\Fundraising\Donor  $donor
     * @return \Illuminate\Http\Response
     */
    public function store(StoreDonation $request, Donor $donor)
    {
        $this->authorize('create', Donation::class);

        $date = new Carbon($request->date);
        if ($date->isFuture()) {
            $date = Carbon::today();
        }

        if ($request->currency == config('fundraising.base_currency')) {
            $exchange_amount = $request->amount;
        } else {
            if (! empty($request->exchange_rate)) {
                $exchange_rate = $request->exchange_rate;
            } else {
                try {
                    $exchange_rate = EzvExchangeRates::getExchangeRate($request->currency, $date);
                } catch (Exception $e) {
                    Log::error($e);
                    return redirect()
                        ->back()
                        ->withInput()
                        ->with('error', __('app.an_error_happened'). ': ' . $e->getMessage());
                }
            }
            $exchange_amount = $request->amount * $exchange_rate;
        }

        $donation = new Donation();
        $donation->date = $date;
        $donation->amount = $request->amount;
        $donation->currency = $request->currency;
        $donation->exchange_amount = $exchange_amount;
        $donation->channel = $request->channel;
        $donation->purpose = $request->purpose;
        $donation->reference = $request->reference;
        $donation->in_name_of = $request->in_name_of;
        $donation->thanked = ! empty($request->thanked) ? Carbon::now() : null;

        $donor->addDonation($donation);

        return response()->json([
            'message' => __('fundraising.donation_registered', [ 'amount' => $request->amount, 'currency' => $request->currency ]),
        ]);
    }

    /**
     * Updates a donation.
     *
     * @param  \App\Http\Requests\Fundraising\StoreDonation  $request
     * @param  \App\Models\Fundraising\Donation  $donation
     * @return \Illuminate\Http\Response
     */
    public function update(StoreDonation $request, Donation $donation)
    {
        $this->authorize('update', $donation);

        $date = new Carbon($request->date);
        if ($date->isFuture()) {
            $date = Carbon::today();
        }

        if ($request->currency == config('fundraising.base_currency')) {
            $exchange_amount = $request->amount;
        } else {
            if (! empty($request->exchange_rate)) {
                $exchange_rate = $request->exchange_rate;
            } else {
                try {
                    $exchange_rate = EzvExchangeRates::getExchangeRate($request->currency, $date);
                } catch (Exception $e) {
                    Log::error($e);
                    return redirect()
                        ->back()
                        ->withInput()
                        ->with('error', __('app.an_error_happened'). ': ' . $e->getMessage());
                }
            }
            $exchange_amount = $request->amount * $exchange_rate;
        }

        $donation->date = $date;
        $donation->amount = $request->amount;
        $donation->currency = $request->currency;
        $donation->exchange_amount = $exchange_amount;
        $donation->channel = $request->channel;
        $donation->purpose = $request->purpose;
        $donation->reference = $request->reference;
        $donation->in_name_of = $request->in_name_of;
        $donation->thanked = ! empty($request->thanked) ? ($donation->thanked !== null ? $donation->thanked : Carbon::now()) : null;
        $donation->save();

        return response()->json([
            'message' => __('fundraising.donation_updated'),
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(Donation $donation)
    {
        $this->authorize('delete', $donation);

        $donation->delete();

        return response()->json([
            'message' => __('fundraising.donation_deleted'),
        ]);
    }

    /**
     * Gets the number of registrations per time unit.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function registrations(Request $request)
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
}
