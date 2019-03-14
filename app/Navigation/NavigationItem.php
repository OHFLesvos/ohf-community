<?php

namespace App\Navigation;

interface NavigationItem {

    /**
     * @return string
     */
    public function getRoute(): string;

    /**
     * @return string
     */
    public function getCaption(): string;

    /**
     * @return string
     */
    public function getIcon(): string;

    /**
     * @return string|array
     */
    public function getActive();

    /**
     * @return bool
     */
    public function isAuthorized(): bool;

    /**
     * @return string
     */
    public function getBadge();

}
