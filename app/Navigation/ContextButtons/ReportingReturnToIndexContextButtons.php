<?php

namespace App\Navigation\ContextButtons;

use Illuminate\View\View;

class ReportingReturnToIndexContextButtons implements ContextButtons {

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
