<?php

namespace App\Settings\Accounting;

use App\Settings\BaseSettingsField;
use Illuminate\Support\Facades\Gate;

class TransactionReceiptNoCorrectionUse extends BaseSettingsField
{
    public function section(): string
    {
        return 'accounting';
    }

    public function label(): string
    {
        return __('accounting.use_receipt_no_correction');
    }

    public function defaultValue()
    {
        return false;
    }

    public function formType(): string
    {
        return 'checkbox';
    }

    public function authorized(): bool
    {
        return Gate::allows('configure-accounting');
    }
}
