<?php

namespace App\Http\Controllers\Library;

use App\Http\Controllers\SettingsController;
use App\CouponType;

class LibrarySettingsController extends SettingsController
{
    public const DEFAULT_LENING_DURATION_DAYS = 14;

    protected function getSections() {
        return [ ];
    }

    protected function getSettings() {
        return [
            'library.default_lening_duration_days' => [
                'default' => self::DEFAULT_LENING_DURATION_DAYS,
                'form_type' => 'number',
                'form_args' => [ 'min' => 1 ],
                'form_validate' => 'required|numeric|min:1',
                'label_key' => 'library.default_lening_duration_days_in_days',
            ]
        ];
    }

    protected function getUpdateRouteName() {
        return 'library.settings.update';
    }

    protected function getRedirectRouteName() {
        return 'library.lending.index';
    }

}
