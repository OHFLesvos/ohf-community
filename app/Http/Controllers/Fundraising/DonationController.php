<?php

namespace App\Http\Controllers\Fundraising;

use App\Exports\Fundraising\DonationsExport;
use App\Http\Controllers\Controller;
use App\Imports\Fundraising\DonationsImport;
use App\Models\Fundraising\Donation;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Validator;

class DonationController extends Controller
{
    /**
     * Exports all donations
     *
     * @return \Illuminate\Http\Response
     */
    public function export()
    {
        $this->authorize('viewAny', Donation::class);

        $file_name = config('app.name') . ' - ' .__('fundraising.donations') . ' (' . Carbon::now()->toDateString() . ')';
        $extension = 'xlsx';

        return (new DonationsExport())->download($file_name . '.' . $extension);
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
