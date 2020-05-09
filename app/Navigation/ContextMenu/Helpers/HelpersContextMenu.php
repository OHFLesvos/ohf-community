<?php

namespace App\Navigation\ContextMenu\Helpers;

use App\Models\Helpers\Helper;
use App\Models\Helpers\Responsibility;
use App\Navigation\ContextMenu\ContextMenu;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class HelpersContextMenu implements ContextMenu
{
    public function getItems(): array
    {
        return [
            'badges' => [
                'url' => route('badges.index', ['source' => 'helpers']),
                'caption' => __('badges.badges'),
                'icon' => 'id-card',
                'authorized' => Auth::user()->can('viewAny', Helper::class) && Gate::allows('create-badges'),
            ],
            'import' => [
                'url' => route('people.helpers.import'),
                'caption' => __('app.import'),
                'icon' => 'upload',
                'authorized' => Auth::user()->can('import', Helper::class),
            ],
            'responsibilities' => [
                'url' => route('people.helpers.responsibilities.index'),
                'caption' => __('responsibilities.responsibilities'),
                'icon' => 'tasks',
                'authorized' => Auth::user()->can('viewAny', Responsibility::class),
            ],
        ];
    }

}
