<?php

namespace App\Navigation\ContextButtons;

use Illuminate\View\View;
use Illuminate\Support\Facades\Gate;

class LibraryLendingIndexContextButtons implements ContextButtons {

    public function getItems(View $view): array
    {
        return [
            'settings' => [
                'url' => route('library.settings.edit'),
                'caption' => __('app.settings'),
                'icon' => 'cogs',
                'authorized' => Gate::allows('configure-library')
            ]
        ];

    }

}
