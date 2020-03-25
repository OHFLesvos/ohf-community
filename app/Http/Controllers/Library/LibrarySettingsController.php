<?php

namespace App\Http\Controllers\Library;

use App\Http\Controllers\Settings\SettingsController;

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
            ],
            'library.max_books_per_person' => [
                'default' => null,
                'form_type' => 'number',
                'form_args' => [ 'min' => 1 ],
                'form_validate' => 'nullable|numeric|min:1',
                'label_key' => 'library.max_amount_of_books_person_can_lend',
            ],
            'google.api_key' => [
                'default' => '',
                'form_type' => 'text',
                'label_key' => 'app.google_api_key',
            ],
        ];
    }

    protected function getUpdateRouteName() {
        return 'library.settings.update';
    }

    protected function getRedirectRouteName() {
        return 'library.lending.index';
    }

}
