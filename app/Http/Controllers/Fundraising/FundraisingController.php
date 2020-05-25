<?php

namespace App\Http\Controllers\Fundraising;

use App\Http\Controllers\Controller;
use App\Models\Fundraising\Donation;
use App\Models\Fundraising\Donor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class FundraisingController extends Controller
{
    public function index(Request $request)
    {
        $this->authorize('viewAny', Donation::class);

        return view('fundraising.index', [
            'permissions' => [
                'view-donors' => $request->user()->can('viewAny', Donor::class),
                'create-donors' => $request->user()->can('create', Donor::class),
                'view-donations' => $request->user()->can('viewAny', Donation::class),
                'create-donations' => $request->user()->can('create', Donation::class),
                'view-fundraising-reports' => Gate::allows('view-fundraising-reports'),
            ],
        ]);
    }
}
