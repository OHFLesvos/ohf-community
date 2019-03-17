<?php

namespace Modules\Fundraising\Widgets;

use App\Widgets\Widget;

use Modules\Fundraising\Entities\Donor;
use Modules\Fundraising\Entities\Donation;

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
        return 'dashboard.widgets.donors';
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