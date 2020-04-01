<?php

namespace App\Navigation\Drawer;

interface NavigationItem
{
    public function getRoute(): string;

    public function getCaption(): string;

    public function getIcon(): string;

    public function getActive();

    public function isAuthorized(): bool;

    public function getBadge();
}
