<?php

namespace App\Navigation\ContextButtons\Changelog;

use App\Navigation\ContextButtons\ContextButtons;

use Illuminate\View\View;

class ChangelogContextButtons implements ContextButtons {

    public function getItems(View $view): array
    {
        return [
            'back' => [
                'url' => url()->previous(),
                'caption' => __('app.close'),
                'icon' => 'times-circle',
                'authorized' => true
            ]
        ];
    }

}
