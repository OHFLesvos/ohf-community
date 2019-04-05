<?php

namespace Modules\Shop\Http\Controllers;

use App\CouponType;
use App\Http\Controllers\Settings\SettingsController;

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
                'label_key' => 'people.coupon',
            ],
            'shop.barber.allow_remove' => [
                'default' => false,
                'form_type' => 'checkbox',
                'form_validate' => 'nullable|boolean',
                'label_key' => 'shop::shop.allow_remove_reservation',
            ]
        ];
    }

    protected function getUpdateRouteName() {
        return 'shop.barber.settings.update';
    }

    protected function getRedirectRouteName() {
        return 'shop.barber.index';
    }

}
