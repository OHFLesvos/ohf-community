<?php

namespace App\Http\Controllers\Accounting;

use App\Http\Controllers\Settings\SettingsController;

class AccountingSettingsController extends SettingsController
{
    public const CATEGORIES_SETTING_KEY = 'accounting.transactions.categories';
    public const PROJECTS_SETTING_KEY = 'accounting.transactions.projects';

    protected function getSections()
    {
        return [ ];
    }

    protected function getSettings()
    {
        return [
            self::CATEGORIES_SETTING_KEY => [
                'default' => '',
                'form_type' => 'textarea',
                'label_key' => 'app.categories',
                'form_help' => 'Separate items by newline',
                'setter' => fn ($value) => preg_split('/(\s*[,\/|]\s*)|(\s*\n\s*)/', $value),
                'getter' => fn ($value) => implode("\n", $value),
            ],
            self::PROJECTS_SETTING_KEY => [
                'default' => '',
                'form_type' => 'textarea',
                'label_key' => 'app.projects',
                'form_help' => 'Separate items by newline',
                'setter' => fn ($value) => preg_split("/(\s*[,\/|]\s*)|(\s*\n\s*)/", $value),
                'getter' => fn ($value) => implode("\n", $value),
            ],
        ];
    }

    protected function getUpdateRouteName()
    {
        return 'accounting.settings.update';
    }

    protected function getRedirectRouteName()
    {
        return 'accounting.transactions.summary';
    }

}
