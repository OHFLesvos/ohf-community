<?php

namespace App\Widgets\Fundraising;

use App\Widgets\Widget;

use App\Models\Fundraising\Donor;
use App\Models\Fundraising\Donation;

use Illuminate\Support\Facades\Auth;

use Carbon\Carbon;

class DonorsWidget implements Widget
{
    function authorize(): bool
    {
        return Auth::user()->can('list', Donor::class);
    }

    function view(): string
    {
        return 'fundraising.dashboard.widgets.donors';
    }

    function args(): array {
        return [
            'num_donors' => Donor::count(),
            'num_donations_month' => Donation::whereDate('date', '>', Carbon::now()->startOfMonth())->count(),
            'num_donations_year' => Donation::whereDate('date', '>', Carbon::now()->startOfYear())->count(),
            'num_donations_total' => Donation::count(),
        ];
    }
}