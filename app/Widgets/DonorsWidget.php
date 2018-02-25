<?php

namespace App\Widgets;

use App\Donor;
use Illuminate\Support\Facades\Auth;

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
			'latest_donor' => Donor::orderBy('created_at', 'desc')->first(),
        ];
    }
}