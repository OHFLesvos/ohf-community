<?php

namespace App\Services;

use App\Widgets\Widget;

class DashboardWidgetsService {

    private $widgets = [];

    public function define($widgetClass, int $position = null)
    {
        $this->widgets[] = [
            'clazz' => $widgetClass,
            'position' => $position,
        ];
    }

    public function collection()
    {
        return collect($this->widgets)
            ->sortBy('position')
            ->pluck('clazz')
            ->map(function($clazz){
                return new $clazz();
            })
            ->filter(function($w){
                return $w instanceof Widget;
            });
    }

}
