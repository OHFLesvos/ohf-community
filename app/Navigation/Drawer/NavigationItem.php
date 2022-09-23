<?php

namespace App\Navigation\Drawer;

interface NavigationItem
{
    public function getRoute(): string;

    public function getCaption(): string;

    public function getIcon(): string;

    public function getActive(): string|array;

    public function isAuthorized(): bool;
}
