<?php

namespace Modules\Shop\Http\Controllers;

use App\Http\Controllers\Settings\SettingsController;

use Modules\KB\Entities\WikiArticle;

class ShopSettingsController extends SettingsController
{
    protected function getSections() {
        return [
         ];
    }

    protected function getSettings() {
        return [
            'shop.help_article' => is_module_enabled('KB') ? [
                'default' => null,
                'form_type' => 'select',
                'form_list' => WikiArticle::orderBy('title')->get()->pluck('title', 'id')->toArray(),
                'form_placeholder' => __('kb::wiki.select_article'),
                'form_validate' => 'nullable|exists:kb_articles,id',
                'label_key' => 'kb::wiki.help_article',
            ] : null,
        ];
    }

    protected function getUpdateRouteName() {
        return 'shop.settings.update';
    }

    protected function getRedirectRouteName() {
        return 'shop.index';
    }

}
