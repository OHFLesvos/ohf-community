<?php

namespace App\Widgets\People;

use App\Widgets\Widget;

use App\Models\People\Person;

use Illuminate\Support\Facades\Auth;

use Carbon\Carbon;

class PersonsWidget implements Widget
{
    function authorize(): bool
    {
        return Auth::user()->can('list', Person::class);
    }

    function view(): string
    {
        return 'people.dashboard.widgets.persons';
    }

    function args(): array
    {
        return [
            'num_people' => Person::count(),
			'num_people_added_today' => Person::whereDate('created_at', '=', Carbon::today())->count(),
        ];
    }
}