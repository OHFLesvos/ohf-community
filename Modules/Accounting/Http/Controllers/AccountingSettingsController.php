<?php

namespace Modules\Accounting\Http\Controllers;

use App\Http\Controllers\Settings\SettingsController;

class AccountingSettingsController extends SettingsController
{
    protected function getSections() {
        return [ ];
    }

    protected function getSettings() {
        return [
            'accounting.transactions.categories' => [
                'default' => '',
                'form_type' => 'text',
                'label_key' => 'app.categories',
                'form_help' => 'Separate by comma',
                'setter' => function($value){ return preg_split('/(\s*[,\/|]\s*)/', $value); },
                'getter' => function($value) { return implode(', ', $value); },
            ]
        ];
    }

    protected function getUpdateRouteName() {
        return 'accounting.settings.update';
    }

    protected function getRedirectRouteName() {
        return 'accounting.transactions.summary';
    }

}
