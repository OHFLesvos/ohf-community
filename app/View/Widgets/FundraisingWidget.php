<?php

namespace App\View\Widgets;

use App\Models\Fundraising\Donation;
use App\Models\Fundraising\Donor;
use Carbon\Carbon;

class FundraisingWidget implements Widget
{
    public function authorize(): bool
    {
        return request()->user()->can('view-fundraising');
    }

    public function render()
    {
        return view('widgets.fundraising', [
            'num_donors' => Donor::count(),
            'num_donations_month' => Donation::whereDate('date', '>', Carbon::now()->startOfMonth())->count(),
            'num_donations_year' => Donation::whereDate('date', '>', Carbon::now()->startOfYear())->count(),
            'num_donations_total' => Donation::count(),
        ]);
    }
}
