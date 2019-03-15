<?php

namespace App\Navigation\ContextMenu;

use App\Helper;

use Illuminate\Support\Facades\Auth;

class HelpersContextMenu extends BaseContextMenu {

    protected $routeName = 'people.helpers.index';

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
