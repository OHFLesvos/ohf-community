<?php

namespace App\Http\Controllers\Fundraising;

use App\Exports\Fundraising\DonationsExport;
use App\Http\Controllers\Controller;
use App\Http\Requests\Fundraising\StoreDonation;
use App\Imports\Fundraising\DonationsImport;
use App\Models\Fundraising\Donation;
use App\Models\Fundraising\Donor;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;
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
        return view('fundraising.donations.index', [
            'donations' => $query->paginate(100),
        ]);
    }

    /**
     * Register a new donation.
     *
     * @param  \App\Models\Fundraising\Donor  $donor
     * @return \Illuminate\Http\Response
     */
    public function create(Donor $donor)
    {
        $this->authorize('create', Donation::class);

        return view('fundraising.donations.create', [
            'donor' => $donor,
            'currencies' => config('fundraising.currencies'),
            'channels' => Donation::channels(),
        ]);
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
        $donor->donations()->save($donation);
        return redirect()
            ->route('fundraising.donors.show', $donor)
            ->with('success', __('fundraising.donation_registered', [ 'amount' => $request->amount, 'currency' => $request->currency ]));
    }

    /**
     * Edit a donation.
     *
     * @param  \App\Models\Fundraising\Donor  $donor
     * @param  \App\Models\Fundraising\Donation  $donation
     * @return \Illuminate\Http\Response
     */
    public function edit(Donor $donor, Donation $donation)
    {
        $this->authorize('update', $donation);

        return view('fundraising.donations.edit', [
            'donor' => $donor,
            'donation' => $donation,
            'currencies' => config('fundraising.currencies'),
            'channels' => Donation::channels(),
        ]);
    }

    /**
     * Updates new donation.
     *
     * @param  \App\Http\Requests\Fundraising\StoreDonation  $request
     * @param  \App\Models\Fundraising\Donor  $donor
     * @param  \App\Models\Fundraising\Donation  $donation
     * @return \Illuminate\Http\Response
     */
    public function update(StoreDonation $request, Donor $donor, Donation $donation)
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
        $donation->thanked = ! empty($request->thanked) ? Carbon::now() : null;
        $donation->save();
        return redirect()
            ->route('fundraising.donors.show', $donor)
            ->with('success', __('fundraising.donation_updated'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Fundraising\Donor  $donor
     * @return \Illuminate\Http\Response
     */
    public function destroy(Donor $donor, Donation $donation)
    {
        $this->authorize('delete', $donation);

        $donation->delete();
        return redirect()
            ->route('fundraising.donors.show', $donor)
            ->with('success', __('fundraising.donation_deleted'));
    }

    /**
     * Exports all donations
     *
     * @return \Illuminate\Http\Response
     */
    public function export()
    {
        $this->authorize('list', Donation::class);

        $file_name = config('app.name') . ' - ' .__('fundraising.donations') . ' (' . Carbon::now()->toDateString() . ')';
        $extension = 'xlsx';

        return (new DonationsExport())->download($file_name . '.' . $extension);
    }

    /**
     * Exports the donations of a donor
     *
     * @param  \App\Models\Fundraising\Donor  $donor
     * @return \Illuminate\Http\Response
     */
    public function exportDonor(Donor $donor)
    {
        $this->authorize('list', Donation::class);

        $file_name = config('app.name') . ' - ' .__('fundraising.donations') . ' - ' . $donor->full_name . ' (' . Carbon::now()->toDateString() . ')';
        $extension = 'xlsx';

        return (new DonationsExport($donor))->download($file_name . '.' . $extension);
    }

    public function import()
    {
        $this->authorize('create', Donation::class);

        return view('fundraising.donations.import', [
            'types' => [
                'stripe' => 'Stripe',
            ],
            'type' => 'stripe',
        ]);
    }

    public function doImport(Request $request)
    {
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

        (new DonationsImport())->import($request->file('file'));

        return redirect()
            ->route('fundraising.donations.index')
            ->with('success', __('app.import_successful'));
    }

}
