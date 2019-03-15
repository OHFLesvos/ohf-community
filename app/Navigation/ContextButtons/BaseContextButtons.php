<?php

namespace App\Navigation\ContextButtons;

abstract class BaseContextButtons {

    /**
     * @var array
     */
    protected $routeNames;

    /**
     * @var string
     */
    protected $routeName;

    public function getRouteNames(): array
    {
        if (isset($this->routeNames) && !empty($this->routeNames) && is_array($this->routeNames)) {
            return $this->routeNames;
        }
        if (isset($this->routeName) && !empty($this->routeName) && is_string($this->routeName)) {
            return [$this->routeName];
        }
        throw new \Exception('Parameter \'' . $variable . '\' not defined in '. __CLASS__);
    }

}
