<?php

namespace Modules\Shop\Http\Controllers;

use App\Http\Controllers\Settings\SettingsController;

use Modules\Bank\Entities\CouponType;

use Modules\KB\Entities\WikiArticle;

class BarberShopSettingsController extends SettingsController
{
    protected function getSections() {
        return [ ];
    }

    protected function getSettings() {
        return [
            'shop.barber.coupon_type' => [
                'default' => null,
                'form_type' => 'select',
                'form_list' => CouponType::orderBy('name')->get()->pluck('name', 'id')->toArray(),
                'form_placeholder' => __('people::people.select_coupon_type'),
                'form_validate' => 'nullable|exists:coupon_types,id',
                'label_key' => 'bank::coupons.coupon',
            ],
            'shop.barber.allow_remove' => [
                'default' => false,
                'form_type' => 'checkbox',
                'form_validate' => 'nullable|boolean',
                'label_key' => 'shop::shop.allow_remove_reservation',
            ],
            'shop.barber.help_article' => is_module_enabled('KB') ? [
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
        return 'shop.barber.settings.update';
    }

    protected function getRedirectRouteName() {
        return 'shop.barber.index';
    }

}
