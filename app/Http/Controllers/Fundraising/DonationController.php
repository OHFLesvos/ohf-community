<?php

namespace App\Http\Controllers\Fundraising;

use App\Http\Controllers\Controller;
use App\Imports\Fundraising\DonationsImport;
use App\Models\Fundraising\Donation;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Validator;

class DonationController extends Controller
{
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
