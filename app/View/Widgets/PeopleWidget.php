<?php

namespace App\View\Widgets;

use App\Models\People\Person;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class PeopleWidget implements Widget
{
    public function authorize(): bool
    {
        return Auth::user()->can('viewAny', Person::class);
    }

    public function view(): string
    {
        return 'widgets.people';
    }

    public function args(): array
    {
        return [
            'num_people' => Person::count(),
            'num_people_added_today' => Person::whereDate('created_at', '=', Carbon::today())->count(),
        ];
    }
}
