<?php

namespace App\Services;

class DashboardWidgetsService {

    private $widgets = [];

    public function define($widgetClass, int $position = null)
    {
        $widget = new $widgetClass();
        if ($position !== null) {
            array_splice($this->widgets, $position, 0, [$widget]);
        } else {
            $this->widgets[] = $widget;
        }
    }

    public function collection()
    {
        return collect($this->widgets);
    }

}
