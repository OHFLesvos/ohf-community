<?php

namespace App\Http\Controllers\Fundraising;

use App\Http\Controllers\Controller;
use App\Http\Resources\Fundraising\Donor as DonorResource;
use App\Models\Comment;
use App\Models\Fundraising\Donation;
use App\Models\Fundraising\Donor;
use App\Tag;
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

        return view('fundraising.donors.create', []);
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

        $can_view_donations = $request->user()->can('viewAny', Donation::class);

        return view('fundraising.donors.show', [
            'donor' => $donor,
            'donorResource' => [
                'id' => $donor->id,
                'salutation' => $donor->salutation,
                'first_name' => $donor->first_name,
                'last_name' => $donor->last_name,
                'company' => $donor->company,
                'fullAddress' => $donor->fullAddress,
                'email' => $donor->email,
                'phone' => $donor->phone,
                'language' => $donor->language,
                'created_at' => $donor->created_at,
                'updated_at' => $donor->updated_at,
                'can_create_tag' => $request->user()->can('create', Tag::class),
                'can_create_comment' => $request->user()->can('create', Comment::class),
                'can_create_donation' => $request->user()->can('create', Donation::class),
                'can_view_donations' => $can_view_donations,
                'donations_count' => $can_view_donations ? $donor->donations()->count() : null,
                'comments_count' => $donor->comments()->count(),
            ],
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
