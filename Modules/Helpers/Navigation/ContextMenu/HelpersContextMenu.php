<?php

namespace Modules\Helpers\Navigation\ContextMenu;

use App\Navigation\ContextMenu\ContextMenu;

use Modules\Helpers\Entities\Helper;
use Modules\Helpers\Entities\Responsibility;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class HelpersContextMenu implements ContextMenu {

    public function getItems(): array
    {
        return [
            'badges' => is_module_enabled('Badges') ? [
                'url' => route('badges.index', ['source' => 'helpers']),
                'caption' => __('badges::badges.badges'),
                'icon' => 'id-card',
                'authorized' => Auth::user()->can('list', Helper::class) && Gate::allows('create-badges')
            ] : null,            
            'import' => [
                'url' => route('people.helpers.import'),
                'caption' => __('app.import'),
                'icon' => 'upload',
                'authorized' => Auth::user()->can('import', Helper::class)
            ],
            'responsibilities' => [
                'url' => route('people.helpers.responsibilities.index'),
                'caption' => __('helpers::responsibilities.responsibilities'),
                'icon' => 'tasks',
                'authorized' => Auth::user()->can('list', Responsibility::class)
            ],            
        ];
    }

}
