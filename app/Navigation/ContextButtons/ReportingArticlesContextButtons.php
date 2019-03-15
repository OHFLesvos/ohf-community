<?php

namespace App\Navigation\ContextButtons;

use Illuminate\View\View;

class ReportingArticlesContextButtons implements ContextButtons {

    public function getItems(View $view): array
    {
        if (!preg_match('#/reporting/#', url()->previous())) {
            session(['articleReportingBackUrl' => url()->previous()]);
        }
        return [
            'back' => [
                'url' => session('articleReportingBackUrl', route('reporting.index')),
                'caption' => __('app.close'),
                'icon' => 'times-circle',
                'authorized' => true
            ]
        ];
    }

}
