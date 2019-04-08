<?php

namespace Modules\Helpers\Widgets;

use App\Widgets\Widget;

use Modules\Helpers\Entities\Helper;

use Illuminate\Support\Facades\Auth;

class HelpersWidget implements Widget
{
    function authorize(): bool
    {
        return Auth::user()->can('list', Helper::class);
    }

    function view(): string
    {
        return 'helpers::dashboard.widgets.helpers';
    }

    function args(): array {
        return [
            'active' => Helper::active()->count(),
            'trial' => Helper::trial()->count(),
            'applicants' => Helper::applicants()->count(),
        ];
    }
}