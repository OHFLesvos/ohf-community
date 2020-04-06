<?php

namespace App\Settings\Bank;

use App\Settings\BaseSettingsField;
use Illuminate\Support\Facades\Gate;

class UndoCouponHandoutGracePeriod extends BaseSettingsField
{
    public function section(): string
    {
        return 'bank';
    }

    public function label(): string
    {
        return __('coupons.undo_coupon_handout_grace_period_seconds');
    }

    public function defaultValue()
    {
        return config('bank.undo_coupon_handout_grace_period');
    }

    public function formType(): string
    {
        return 'number';
    }

    public function formArgs(): ?array
    {
        return [
            'min' => 1,
        ];
    }

    public function formValidate(): ?array
    {
        return [
            'required',
            'numeric',
            'min:1',
        ];
    }

    public function authorized(): bool
    {
        return Gate::allows('configure-bank');
    }
}
