<?php

namespace App\Http\Controllers\Fundraising;

use App\Http\Controllers\Controller;
use App\Http\Resources\Fundraising\Donor as DonorResource;
use App\Models\Fundraising\Donor;
use Illuminate\Http\Request;

class DonorController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $this->authorize('viewAny', Donor::class);

        $request->validate([
            'tag' => [
                'nullable',
                'filled',
                'alpha_dash',
            ],
        ]);

        return view('fundraising.donors.index', [
            'tag' => $request->input('tag', null),
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

        return view('fundraising.donors.create');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Fundraising\Donor  $donor
     * @return \Illuminate\Http\Response
     */
    public function show(Donor $donor, Request $request)
    {
        $this->authorize('view', $donor);

        return view('fundraising.donors.show', [
            'donor' => $donor,
            'donorResource' => (new DonorResource($donor, true))->resolve(),
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Fundraising\Donor  $donor
     * @return \Illuminate\Http\Response
     */
    public function edit(Donor $donor)
    {
        $this->authorize('update', $donor);

        return view('fundraising.donors.edit', [
            'donor' => $donor,
            'donorResource' => (new DonorResource($donor))->resolve(),
        ]);
    }
}
