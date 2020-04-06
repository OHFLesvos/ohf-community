<?php

namespace App\Settings\Common;

use App\Settings\BaseSettingsField;
use Gumlet\ImageResize;
use Illuminate\Support\Facades\Gate;

class SignetFile extends BaseSettingsField
{
    public function section(): string
    {
        return 'common';
    }

    public function label(): string
    {
        return __('app.signet');
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
        return 'public/common';
    }

    public function setter($value)
    {
        $image = new ImageResize($value->getRealPath());
        $image->resizeToBestFit(30, 30, true);
        $image->save($value->getRealPath());
        return $value;
    }

    public function authorized(): bool
    {
        return Gate::allows('configure-common-settings');
    }
}
