<?php

namespace App\Settings\Shop;

use App\Models\Bank\CouponType as BankCouponType;
use App\Settings\BaseSettingsField;
use Illuminate\Support\Facades\Gate;

class CouponType extends BaseSettingsField
{
    public function section(): string
    {
        return 'shop';
    }

    public function label(): string
    {
        return __('coupons.coupon');
    }

    public function defaultValue()
    {
        return null;
    }

    public function formType(): string
    {
        return 'select';
    }

    public function formList(): ?array
    {
        return BankCouponType::orderBy('name')
            ->where('qr_code_enabled', true)
            ->get()
            ->pluck('name', 'id')
            ->toArray();
    }

    public function formValidate(): ?array
    {
        return [
            'nullable',
            'exists:coupon_types,id',
        ];
    }

    public function formPlaceholder(): ?string
    {
        return __('people.select_coupon_type');
    }

    public function authorized(): bool
    {
        return Gate::allows('configure-shop');
    }
}
