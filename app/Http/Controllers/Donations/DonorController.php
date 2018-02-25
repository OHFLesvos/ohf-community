<?php

namespace App\Http\Controllers\Donations;

use App\Donor;
use App\Donation;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Util\CountriesExtended;
use App\Http\Requests\Donations\StoreDonor;
use App\Http\Requests\Donations\StoreDonation;
use Carbon\Carbon;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;

class DonorController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $this->authorize('list', Donor::class);

        $query = Donor::orderBy('name');
        if (isset($request->filter)) {
            $query->where('name', 'LIKE', '%' . $request->filter . '%');
        }
        return view('donations.donors.index', [
            'donors' => $query->paginate(),
            'filter' => $request->filter,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->authorize('create', Donor::class);

        return view('donations.donors.create', [
            'countries' => CountriesExtended::getList('en'),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\Donations\StoreDonor  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreDonor $request)
    {
        $this->authorize('create', Donor::class);

        $donor = new Donor();
        $donor->name = $request->name;
        $donor->address = $request->address;
        $donor->zip = $request->zip;
        $donor->city = $request->city;
        $donor->country = $request->country;
        $donor->email = $request->email;
        $donor->phone = $request->phone;
        $donor->save();
        return redirect()->route('donors.show', $donor)
            ->with('success', __('donations.donor_added'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Donor  $donor
     * @return \Illuminate\Http\Response
     */
    public function show(Donor $donor)
    {
        $this->authorize('view', $donor);

        return view('donations.donors.show', [
            'donor' => $donor,
            'donations' => $donor->donations()->orderBy('date', 'desc')->orderBy('created_at', 'desc')->paginate(),
            'currencies' => Config::get('donations.currencies'),
            'origins' => Donation::select('origin')->distinct()->get()->pluck('origin')->toArray(),
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Donor  $donor
     * @return \Illuminate\Http\Response
     */
    public function edit(Donor $donor)
    {
        $this->authorize('update', $donor);

        return view('donations.donors.edit', [
            'donor' => $donor,
            'countries' => CountriesExtended::getList('en'),
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\Donations\StoreDonor  $request
     * @param  \App\Donor  $donor
     * @return \Illuminate\Http\Response
     */
    public function update(StoreDonor $request, Donor $donor)
    {
        $this->authorize('update', $donor);

        $donor->name = $request->name;
        $donor->address = $request->address;
        $donor->zip = $request->zip;
        $donor->city = $request->city;
        $donor->country = $request->country;
        $donor->email = $request->email;
        $donor->phone = $request->phone;
        $donor->save();
        return redirect()->route('donors.show', $donor)
            ->with('success', __('donations.donor_updated'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Donor  $donor
     * @return \Illuminate\Http\Response
     */
    public function destroy(Donor $donor)
    {
        $this->authorize('delete', $donor);

        $donor->delete();
        return redirect()->route('donors.index')
            ->with('success', __('donations.donor_deleted'));
    }

    /**
     * Exports a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function export()
    {
        $this->authorize('list', Donor::class);

        \Excel::create('OHF_Community_Donors_' . Carbon::now()->toDateString(), function($excel) {
            $excel->sheet(__('donations.donors'), function($sheet) {
                $sheet->setOrientation('landscape');
                $sheet->freezeFirstRow();
                $sheet->loadView('donations.donors.export',[
                    'donors' => Donor::orderBy('name')->get(),
                ]);
            });
        })->export('xls');
    }

    /**
     * Stores a new donation.
     *
     * @param  \App\Http\Requests\Donations\StoreDonation  $request
     * @param  \App\Donor  $donor
     * @return \Illuminate\Http\Response
     */
    public function storeDonation(StoreDonation $request, Donor $donor)
    {
        $this->authorize('create', Donation::class);

        if ($request->currency == Config::get('donations.base_currency')) {
            $exchange_amount = $request->amount;
        } else {
            try {
                $exchange_amount = $request->amount * self::getExchangeRate($request->currency);
            } catch (\Exception $e) {
                Log::error($e);
                return redirect()
                    ->back()
                    ->withInput()
                    ->with('error', __('app.an_error_happened'). ': ' . $e->getMessage());
            }
        }

        $donation = new Donation();
        $donation->date = $request->date;
        $donation->amount = $request->amount;
        $donation->currency = $request->currency;
        $donation->exchange_amount = $exchange_amount;
        $donation->origin = $request->origin;
        $donor->donations()->save($donation);
        return redirect()->back();
    }

    public static function getExchangeRate($currency) : float {
        $rate = session('exchangeRate_' . $currency, null);
        if ($rate == null) {
            $rate = self::getExchangeRateFromWeb($currency);
            session(['exchangeRate_' . $currency => $rate]);
        }
        return $rate;
    }

    // See https://www.estv.admin.ch/estv/de/home/mehrwertsteuer/dienstleistungen/fremdwaehrungskurse/tageskurse.html
    const EXCHANGE_RATE_XML = 'http://www.pwebapps.ezv.admin.ch/apps/rates/rate/getxml?activeSearchType=today';

    public static function getExchangeRateFromWeb($currency) : float {
        $context  = stream_context_create(array('http' => array('header' => 'Accept: application/xml')));
        $url = self::EXCHANGE_RATE_XML;
        $xml = file_get_contents($url, false, $context);
        $xml = simplexml_load_string($xml);
        foreach ($xml->devise as $devise) {
            if ($devise['code'] == strtolower($currency)) {
                return (float)$devise->kurs;
            }
        }
        throw new Exception('Unable to find current exchange rate for ' . $currency);
    }

}
