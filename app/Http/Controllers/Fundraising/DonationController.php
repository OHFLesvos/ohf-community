<?php

namespace App\Http\Controllers\Fundraising;

use App\Donor;
use App\Donation;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\Fundraising\StoreDonation;
use Carbon\Carbon;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;
use MrCage\EzvExchangeRates\EzvExchangeRates;
use Validator;
use Illuminate\Validation\Rule;
use App\Exports\DonationsExport;

class DonationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $this->authorize('list', Donation::class);

        $query = Donation::orderBy('created_at', 'desc');
        return view('fundraising.donations.index', [
            'donations' => $query->paginate(100),
        ]);
    }

    /**
     * Register a new donation.
     *
     * @param  \App\Donor  $donor
     * @return \Illuminate\Http\Response
     */
    public function create(Donor $donor)
    {
        $this->authorize('create', Donation::class);

        return view('fundraising.donations.create', [
            'donor' => $donor,
            'currencies' => Config::get('fundraising.currencies'),
            'channels' => Donation::select('channel')->distinct()->get()->pluck('channel')->toArray(),
        ]);
    }

    /**
     * Stores a new donation.
     *
     * @param  \App\Http\Requests\Fundraising\StoreDonation  $request
     * @param  \App\Donor  $donor
     * @return \Illuminate\Http\Response
     */
    public function store(StoreDonation $request, Donor $donor)
    {
        $this->authorize('create', Donation::class);

        $date = new Carbon($request->date);
        if ($date->isFuture()) {
            $date = Carbon::today();
        }

        if ($request->currency == Config::get('fundraising.base_currency')) {
            $exchange_amount = $request->amount;
        } else {
            if (!empty($request->exchange_rate)) {
                $exchange_rate = $request->exchange_rate;
            } else {
                try {
                    $exchange_rate = EzvExchangeRates::getExchangeRate($request->currency, $date);
                } catch (\Exception $e) {
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
        $donor->donations()->save($donation);
        return redirect()->route('fundraising.donors.show', $donor)
            ->with('success', __('fundraising.donation_registered', [ 'amount' => $request->amount, 'currency' => $request->currency ]));;
    }

    /**
     * Edit a donation.
     *
     * @param  \App\Donor  $donor
     * @param  \App\Donation  $donation
     * @return \Illuminate\Http\Response
     */
    public function edit(Donor $donor, Donation $donation)
    {
        $this->authorize('update', $donation);

        return view('fundraising.donations.edit', [
            'donor' => $donor,
            'donation' => $donation,
            'currencies' => Config::get('fundraising.currencies'),
            'channels' => Donation::select('channel')->distinct()->get()->pluck('channel')->toArray(),
        ]);
    }

    /**
     * Updates new donation.
     *
     * @param  \App\Http\Requests\Fundraising\StoreDonation  $request
     * @param  \App\Donor  $donor
     * @param  \App\Donation  $donation
     * @return \Illuminate\Http\Response
     */
    public function update(StoreDonation $request, Donor $donor, Donation $donation)
    {
        $this->authorize('update', $donation);

        $date = new Carbon($request->date);
        if ($date->isFuture()) {
            $date = Carbon::today();
        }

        if ($request->currency == Config::get('fundraising.base_currency')) {
            $exchange_amount = $request->amount;
        } else {
            if (!empty($request->exchange_rate)) {
                $exchange_rate = $request->exchange_rate;
            } else {
                try {
                    $exchange_rate = EzvExchangeRates::getExchangeRate($request->currency, $date);
                } catch (\Exception $e) {
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
        $donation->thanked = !empty($request->thanked) ? Carbon::now() : null;
        $donation->save();
        return redirect()->route('fundraising.donors.show', $donor)
            ->with('success', __('fundraising.donation_updated'));;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Donor  $donor
     * @return \Illuminate\Http\Response
     */
    public function destroy(Donor $donor, Donation $donation)
    {
        $this->authorize('delete', $donation);

        $donation->delete();
        return redirect()->route('fundraising.donors.show', $donor)
            ->with('success', __('fundraising.donation_deleted'));
    }

    /**
     * Exports the donations of a donor
     *
     * @param  \App\Donor  $donor
     * @return \Illuminate\Http\Response
     */
    public function export(Donor $donor)
    {
        $this->authorize('list', Donation::class);

        $file_name = Config::get('app.name') . ' ' .__('fundraising.donations') . ' - ' . $donor->full_name . ' (' . Carbon::now()->toDateString() . ')';

        $export = new DonationsExport($donor);
        return $export->download($file_name . '.' . 'xlsx');
    }

    function import() {
        $this->authorize('create', Donation::class);

        return view('fundraising.donations.import', [
            'types' => [ 
                'stripe' => 'Stripe'
            ],
            'type' => 'stripe',
        ]);
    }

    function doImport(Request $request) {
        $this->authorize('create', Donation::class);

        Validator::make($request->all(), [
            'type' => [
                'required', 
                Rule::in([ 'stripe' ]),
            ],
            'file' => [
                'required', 
                'file',
            ],
        ])->validate();

        $file = $request->file('file');
        \Excel::selectSheets()->load($file, function($reader) {
            $reader->each(function($sheet) {
                $sheet->each(function($row) {
                    if ($row->status == 'Paid') {

                        $donor = Donor
                            ::where('email', $row->customer_email)
                            ->first();
                        if ($donor == null) {
                            $donor = new Donor();
                            $donor->first_name = preg_replace('/@.*$/', '', $row->customer_email);
                            $donor->email = $row->customer_email;
                            $donor->save();
                        }

                        $date = new Carbon($row->created_utc);
                        $amount = $row->amount;
                        $currency = strtoupper($row->currency);
                        if ($currency != Config::get('fundraising.base_currency')) {
                            $exchange_rate = EzvExchangeRates::getExchangeRate($currency, $date);
                            $exchange_amount = $amount * $exchange_rate;
                        } else {
                            $exchange_amount = $amount;
                        }
            
                        $donation = Donation
                             ::where('channel', 'Stripe')
                             ->where('reference', $row->id)
                             ->first();
                        if ($donation == null) {
                            $donation = new Donation();
                            $donation->date = $date;
                            $donation->amount = $amount;
                            $donation->currency = $currency;
                            $donation->exchange_amount = $exchange_amount;
                            $donation->channel = 'Stripe';
                            $donation->reference = $row->id;
                            $donation->purpose = $row->description;
                            $donor->donations()->save($donation);
                        }
                    }
                });
            });
        });

		return redirect()->route('fundraising.donations.index')
				->with('success', __('app.import_successful'));		
    }

    /**
     * Store donation and donor supplied by RaiseNow Webhook
     */
    public function raiseNowWebHookListener(Request $request) {
        $data = $request->all();

        Log::info("Donation webhook", $data);

        if (!empty($request->stored_customer_firstname) && !empty($request->stored_customer_lastname) && !empty($request->stored_customer_street) && !empty($request->stored_customer_city)) {
            $street = $request->stored_customer_street . (!empty($request->stored_customer_street_number) ? ' ' . $request->stored_customer_street_number : '');
            $donor = Donor
                ::where('first_name', $request->stored_customer_firstname)
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
                $donor->language = $request->language;
                $donor->save();
            }

            $date = new Carbon($request->created);
            $amount = $request->amount / 100;
            $currency = strtoupper($request->currency);
            if ($currency != Config::get('fundraising.base_currency')) {
                $exchange_rate = EzvExchangeRates::getExchangeRate($currency, $date);
                $exchange_amount = $amount * $exchange_rate;
            } else {
                $exchange_amount = $amount;
            }

            $payment_methods = [
                'ECA' => 'MasterCard',
                'MPW' => 'MasterPass',
                'PP'  => 'PayPal',
                'VIS' => 'Visa',
                'TWI' => 'Twint',
                'PFC' => 'PostFinance Card',
                'PEF' => 'PostFinance E-Finance',
                'AMX' => 'American Express',
            ];
            $payment_method = $payment_methods[$request->payment_method] ?? $request->payment_method;
            $donation = new Donation();
            $donation->date = $date;
            $donation->amount = $amount;
            $donation->currency = $currency;
            $donation->exchange_amount = $exchange_amount;
            $donation->channel = 'RaiseNow (' . $payment_method . ')';
            $donation->purpose = $request->stored_customer_message; // TODO consider stored_customer_purpose
            $donation->reference = $request->epp_transaction_id;
            $donor->donations()->save($donation);
        }
    }
}
