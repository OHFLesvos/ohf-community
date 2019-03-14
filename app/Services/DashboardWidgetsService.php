<?php

namespace App\Services;

class DashboardWidgetsService {

    private $widgets = [];

    public function define($widgetClass, int $position = null)
    {
        $widget = new $widgetClass();
        $this->widgets[] = [
            'widget' => $widget,
            'position' => $position,
        ];
    }

    public function collection()
    {
        return collect($this->widgets)->sortBy('position')->pluck('widget');
    }

}
