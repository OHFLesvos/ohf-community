<?php

namespace App\Widgets\Helpers;

use App\Models\Helpers\Helper;
use App\Widgets\Widget;
use Illuminate\Support\Facades\Auth;

class HelpersWidget implements Widget
{
    public function authorize(): bool
    {
        return Auth::user()->can('list', Helper::class);
    }

    public function view(): string
    {
        return 'helpers.dashboard.widgets.helpers';
    }

    public function args(): array
    {
        return [
            'active' => Helper::active()->count(),
        ];
    }
}
