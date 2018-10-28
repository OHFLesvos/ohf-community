<?php

namespace App\Widgets;

use App\Helper;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class HelpersWidget implements Widget
{
    function authorize(): bool
    {
        return Auth::user()->can('list', Helper::class);
    }

    function view(): string
    {
        return 'dashboard.widgets.helpers';
    }

    function args(): array {
        return [
            'active' => Helper::active()->count(),
            'trial' => Helper::trial()->count(),
            'applicants' => Helper::applicants()->count(),
        ];
    }
}