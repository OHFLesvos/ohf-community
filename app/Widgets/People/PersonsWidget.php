<?php

namespace App\Widgets\People;

use App\Models\People\Person;
use App\Widgets\Widget;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class PersonsWidget implements Widget
{
    public function authorize(): bool
    {
        return Auth::user()->can('viewAny', Person::class);
    }

    public function view(): string
    {
        return 'people.dashboard.widgets.persons';
    }

    public function args(): array
    {
        return [
            'num_people' => Person::count(),
            'num_people_added_today' => Person::whereDate('created_at', '=', Carbon::today())->count(),
        ];
    }
}
