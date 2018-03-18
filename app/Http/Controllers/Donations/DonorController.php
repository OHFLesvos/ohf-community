<?php

namespace App\Http\Controllers\Donations;

use App\Donor;
use App\Donation;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Util\CountriesExtended;
use App\Http\Requests\Donations\StoreDonor;
use Carbon\Carbon;
use Illuminate\Support\Facades\Config;

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
        $donor->remarks = $request->remarks;
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
        $donor->remarks = $request->remarks;
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
                $sheet->getStyle('I')->getNumberFormat()->setFormatCode(Config::get('donations.base_currency_excel_format'));
                $sheet->getStyle('J')->getNumberFormat()->setFormatCode(Config::get('donations.base_currency_excel_format'));
            });
        })->export('xlsx');
    }

}
