<?php

namespace Modules\Helpers\Navigation\ContextMenu;

use App\Navigation\ContextMenu\ContextMenu;

use Modules\Helpers\Entities\Helper;

use Illuminate\Support\Facades\Auth;

class HelpersContextMenu implements ContextMenu {

    public function getItems(): array
    {
        return [
            'export' => [
                'url' => route('people.helpers.export'),
                'caption' => __('app.export'),
                'icon' => 'download',
                'authorized' => Auth::user()->can('export', Helper::class)
            ],
            'import' => [
                'url' => route('people.helpers.import'),
                'caption' => __('app.import'),
                'icon' => 'upload',
                'authorized' => Auth::user()->can('import', Helper::class)
            ],
        ];
    }

}
