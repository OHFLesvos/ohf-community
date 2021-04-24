<?php

namespace App\Navigation\Drawer;

use Exception;

abstract class BaseNavigationItem implements NavigationItem
{
    protected $route;
    protected $caption;
    protected $icon;
    protected $active;
    protected $authorized;

    public function getRoute(): string
    {
        $this->assertDefined('route');
        return route($this->route);
    }

    public function getCaption(): string
    {
        $this->assertDefined('caption');
        return $this->caption;
    }

    public function getIcon(): string
    {
        $this->assertDefined('icon');
        return $this->icon;
    }

    public function getActive()
    {
        $this->assertDefined('active');
        return $this->active;
    }

    public function isAuthorized(): bool
    {
        $this->assertDefined('authorized');
        return $this->authorized;
    }

    private function assertDefined($variable)
    {
        if (! isset($this->$variable) || empty($this->$variable)) {
            throw new Exception('Parameter \'' . $variable . '\' not defined in '. __CLASS__);
        }
    }
}
