<?php

namespace App\Navigation\ContextButtons\People;

use App\Models\People\Person;
use App\Navigation\ContextButtons\ContextButtons;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class PeopleMaintenanceContextButtons implements ContextButtons
{
    public function getItems(View $view): array
    {
        return [
            'duplicates' =>[
                'url' => route('people.duplicates'),
                'caption' => __('people.find_duplicates'),
                'icon' => 'clone',
                'authorized' => Auth::user()->can('cleanup', Person::class),
            ],
            'back' => [
                'url' => route('people.index'),
                'caption' => __('app.close'),
                'icon' => 'times-circle',
                'authorized' => Auth::user()->can('viewAny', Person::class),
            ],
        ];
    }
}
