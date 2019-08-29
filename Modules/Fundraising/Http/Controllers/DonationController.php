<?php

namespace Modules\Fundraising\Http\Controllers;

use App\Http\Controllers\Controller;

use Modules\Fundraising\Entities\Donor;
use Modules\Fundraising\Entities\Donation;

use Modules\Fundraising\Exports\DonationsExport;
use Modules\Fundraising\Imports\DonationsImport;
use Modules\Fundraising\Http\Requests\StoreDonation;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;

use Carbon\Carbon;

use MrCage\EzvExchangeRates\EzvExchangeRates;

use Validator;

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
        return view('fundraising::donations.index', [
            'donations' => $query->paginate(100),
        ]);
    }

    /**
     * Register a new donation.
     *
     * @param  \Modules\Fundraising\Entities\Donor  $donor
     * @return \Illuminate\Http\Response
     */
    public function create(Donor $donor)
    {
        $this->authorize('create', Donation::class);

        return view('fundraising::donations.create', [
            'donor' => $donor,
            'currencies' => Config::get('fundraising.currencies'),
            'channels' => Donation::select('channel')->distinct()->get()->pluck('channel')->toArray(),
        ]);
    }

    /**
     * Stores a new donation.
     *
     * @param  \Modules\Fundraising\Http\Requests\StoreDonation  $request
     * @param  \Modules\Fundraising\Entities\Donor  $donor
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
            ->with('success', __('fundraising::fundraising.donation_registered', [ 'amount' => $request->amount, 'currency' => $request->currency ]));;
    }

    /**
     * Edit a donation.
     *
     * @param  \Modules\Fundraising\Entities\Donor  $donor
     * @param  \Modules\Fundraising\Entities\Donation  $donation
     * @return \Illuminate\Http\Response
     */
    public function edit(Donor $donor, Donation $donation)
    {
        $this->authorize('update', $donation);

        return view('fundraising::donations.edit', [
            'donor' => $donor,
            'donation' => $donation,
            'currencies' => Config::get('fundraising.currencies'),
            'channels' => Donation::select('channel')->distinct()->get()->pluck('channel')->toArray(),
        ]);
    }

    /**
     * Updates new donation.
     *
     * @param  \Modules\Fundraising\Http\Requests\StoreDonation  $request
     * @param  \Modules\Fundraising\Entities\Donor  $donor
     * @param  \Modules\Fundraising\Entities\Donation  $donation
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
            ->with('success', __('fundraising::fundraising.donation_updated'));;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \Modules\Fundraising\Entities\Donor  $donor
     * @return \Illuminate\Http\Response
     */
    public function destroy(Donor $donor, Donation $donation)
    {
        $this->authorize('delete', $donation);

        $donation->delete();
        return redirect()->route('fundraising.donors.show', $donor)
            ->with('success', __('fundraising::fundraising.donation_deleted'));
    }

    /**
     * Exports all donations
     *
     * @return \Illuminate\Http\Response
     */
    public function export()
    {
        $this->authorize('list', Donation::class);

        $file_name = Config::get('app.name') . ' - ' .__('fundraising::fundraising.donations') . ' (' . Carbon::now()->toDateString() . ')';

        return (new DonationsExport())->download($file_name . '.' . 'xlsx');
    }

    /**
     * Exports the donations of a donor
     *
     * @param  \Modules\Fundraising\Entities\Donor  $donor
     * @return \Illuminate\Http\Response
     */
    public function exportDonor(Donor $donor)
    {
        $this->authorize('list', Donation::class);

        $file_name = Config::get('app.name') . ' - ' .__('fundraising::fundraising.donations') . ' - ' . $donor->full_name . ' (' . Carbon::now()->toDateString() . ')';

        return (new DonationsExport($donor))->download($file_name . '.' . 'xlsx');
    }

    function import() {
        $this->authorize('create', Donation::class);

        return view('fundraising::donations.import', [
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

        (new DonationsImport)->import($request->file('file'));

		return redirect()->route('fundraising.donations.index')
				->with('success', __('app.import_successful'));		
    }

}
