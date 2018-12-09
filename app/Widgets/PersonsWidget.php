<?php

namespace App\Widgets;

use App\Person;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class PersonsWidget implements Widget
{
    function authorize(): bool
    {
        return Gate::allows('manage-people');
    }

    function view(): string
    {
        return 'dashboard.widgets.persons';
    }

    function args(): array {
        return [
            'num_people' => Person::count(),
			'num_people_added_today' => Person::whereDate('created_at', '=', Carbon::today())->count(),
        ];
    }
}