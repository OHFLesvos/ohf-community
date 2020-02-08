<?php

namespace Modules\Shop\Http\Controllers;

use App\Http\Controllers\Settings\SettingsController as BaseSettingsController;

use Modules\Bank\Entities\CouponType;
use Modules\Collaboration\Entities\WikiArticle;

class SettingsController extends BaseSettingsController
{
    protected function getSections() {
        return [
         ];
    }

    protected function getSettings() {
        return [
            'shop.coupon_type' => [
                'default' => null,
                'form_type' => 'select',
                'form_list' => CouponType::orderBy('name')->where('qr_code_enabled', true)->get()->pluck('name', 'id')->toArray(),
                'form_placeholder' => __('people::people.select_coupon_type'),
                'form_validate' => 'nullable|exists:coupon_types,id',
                'label_key' => 'bank::coupons.coupon',
            ],
            'shop.help_article' => is_module_enabled('KB') ? [
                'default' => null,
                'form_type' => 'select',
                'form_list' => WikiArticle::orderBy('title')->get()->pluck('title', 'id')->toArray(),
                'form_placeholder' => __('collaboration::wiki.select_article'),
                'form_validate' => 'nullable|exists:kb_articles,id',
                'label_key' => 'collaboration::wiki.help_article',
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
