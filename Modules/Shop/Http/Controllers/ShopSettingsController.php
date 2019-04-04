<?php

namespace Modules\Shop\Http\Controllers;

use App\Http\Controllers\Settings\SettingsController;

class ShopSettingsController extends SettingsController
{
    protected function getSections() {
        return [
            'coupons' => __('people.coupons'),
         ];
    }

    protected function getSettings() {
        return [
            'shop.coupon_valid_days' => [
                'default' => 1,
                'form_type' => 'number',
                'form_validate' => 'required|numeric|min:1',
                'label_key' => 'people.validity_in_days',
                'section' => 'coupons',
            ],
        ];
    }

    protected function getUpdateRouteName() {
        return 'shop.settings.update';
    }

    protected function getRedirectRouteName() {
        return 'shop.index';
    }

}
