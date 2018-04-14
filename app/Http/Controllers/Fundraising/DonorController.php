<?php

namespace App\Http\Controllers\Fundraising;

use App\Donor;
use App\Donation;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Util\CountriesExtended;
use App\Http\Requests\Fundraising\StoreDonor;
use Carbon\Carbon;
use Illuminate\Support\Facades\Config;
use JeroenDesloovere\VCard\VCard;
use Illuminate\Support\Facades\DB;

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

        $query = Donor
            ::orderBy('first_name')
            ->orderBy('last_name')
            ->orderBy('company');
        if (isset($request->filter)) {
            $query->where(DB::raw('CONCAT(first_name, \' \', last_name)'), 'LIKE', '%' . $request->filter . '%')
                ->orWhere(DB::raw('CONCAT(last_name, \' \', first_name)'), 'LIKE', '%' . $request->filter . '%')
                ->orWhere('company', 'LIKE', '%' . $request->filter . '%');
        }
        return view('fundraising.donors.index', [
            'donors' => $query->paginate(100),
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

        return view('fundraising.donors.create', [
            'countries' => CountriesExtended::getList('en'),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\Fundraising\StoreDonor  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreDonor $request)
    {
        $this->authorize('create', Donor::class);

        $donor = new Donor();
        $donor->first_name = $request->first_name;
        $donor->last_name = $request->last_name;
        $donor->company = $request->company;
        $donor->street = $request->street;
        $donor->zip = $request->zip;
        $donor->city = $request->city;
        $donor->country = $request->country;
        $donor->email = $request->email;
        $donor->phone = $request->phone;
        $donor->remarks = $request->remarks;
        $donor->save();
        return redirect()->route('fundraising.donors.show', $donor)
            ->with('success', __('fundraising.donor_added'));
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

        return view('fundraising.donors.show', [
            'donor' => $donor,
            'donations' => $donor->donations()->orderBy('date', 'desc')->orderBy('created_at', 'desc')->paginate(),
            'currencies' => Config::get('fundraising.currencies'),
            'channels' => Donation::select('channel')->distinct()->get()->pluck('channel')->toArray(),
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

        return view('fundraising.donors.edit', [
            'donor' => $donor,
            'countries' => CountriesExtended::getList('en'),
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\Fundraising\StoreDonor  $request
     * @param  \App\Donor  $donor
     * @return \Illuminate\Http\Response
     */
    public function update(StoreDonor $request, Donor $donor)
    {
        $this->authorize('update', $donor);

        $donor->first_name = $request->first_name;
        $donor->last_name = $request->last_name;
        $donor->company = $request->company;
        $donor->street = $request->street;
        $donor->zip = $request->zip;
        $donor->city = $request->city;
        $donor->country = $request->country;
        $donor->email = $request->email;
        $donor->phone = $request->phone;
        $donor->remarks = $request->remarks;
        $donor->save();
        return redirect()->route('fundraising.donors.show', $donor)
            ->with('success', __('fundraising.donor_updated'));
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
        return redirect()->route('fundraising.donors.index')
            ->with('success', __('fundraising.donor_deleted'));
    }

    /**
     * Exports a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function export()
    {
        $this->authorize('list', Donor::class);

        \Excel::create(Config::get('app.name') . ' ' . __('fundraising.donors') . ' (' . Carbon::now()->toDateString() . ')', function($excel) {
            $excel->sheet(__('fundraising.donors'), function($sheet) {
                $sheet->setOrientation('landscape');
                $sheet->freezeFirstRow();
                $sheet->loadView('fundraising.donors.export',[
                    'donors' => Donor
                        ::orderBy('first_name')
                        ->orderBy('last_name')
                        ->orderBy('company')
                        ->get(),
                ]);
                $sheet->getStyle('I')->getNumberFormat()->setFormatCode(Config::get('fundraising.base_currency_excel_format'));
                $sheet->getStyle('J')->getNumberFormat()->setFormatCode(Config::get('fundraising.base_currency_excel_format'));
            });
        })->export('xlsx');
    }

    /**
     * Download vcard
     * 
     * @param  \App\Donor  $donor
     * @return \Illuminate\Http\Response
     */
    function vcard(Donor $donor)
    {
        $this->authorize('view', $donor);

        // define vcard
        $vcard = new VCard();
        if ($donor->company != null) {
            $vcard->addCompany($donor->company);
        }
        if ($donor->last_name != null || $donor->first_name != null) {
            $vcard->addName($donor->last_name, $donor->first_name, '', '', '');
        }
        if ($donor->email != null) {
            $vcard->addEmail($donor->email);
        }
        if ($donor->phone != null) {
            $vcard->addPhoneNumber($donor->phone, $donor->company != null ? 'WORK' : 'HOME');
        }
        $vcard->addAddress(null, null, $donor->street, $donor->city, null, $donor->zip, $donor->country, ($donor->company != null ? 'WORK' : 'HOME') . ';POSTAL');

        // return vcard as a download
        return $vcard->download();
    }
}
