<?php

namespace App\Settings\Bank;

use App\Settings\BaseSettingsField;
use Gumlet\ImageResize;
use Illuminate\Support\Facades\Gate;

class CodeCardLogo extends BaseSettingsField
{
    public function section(): string
    {
        return 'bank';
    }

    public function label(): string
    {
        return __('bank.logo_on_code_card');
    }

    public function defaultValue()
    {
        return null;
    }

    public function formType(): string
    {
        return 'file';
    }

    public function formArgs(): ?array
    {
        return [
            'accept' => 'image/*',
        ];
    }

    public function formValidate(): ?array
    {
        return [
            'nullable',
            'image',
        ];
    }

    public function formFilePath(): ?string
    {
        return 'public/bank/code_card';
    }

    public function setter($value)
    {
        $image = new ImageResize($value->getRealPath());
        $image->resizeToBestFit(300, 300, true);
        $image->save($value->getRealPath());
        return $value;
    }

    public function authorized(): bool
    {
        return Gate::allows('configure-bank');
    }
}
