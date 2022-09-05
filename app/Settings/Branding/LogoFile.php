<?php

namespace App\Settings\Branding;

use App\Settings\BaseSettingsField;
use Gumlet\ImageResize;
use Illuminate\Support\Facades\Gate;

class LogoFile extends BaseSettingsField
{
    private const MAX_WIDTH = 1000;

    private const MAX_HEIGHT = 1000;

    public function section(): string
    {
        return 'branding';
    }

    public function label(): string
    {
        return __('Logo');
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

    public function formHelp(): ?string
    {
        return __('The image will be visible e.g. on the login screen');
    }

    public function formFilePath(): ?string
    {
        return 'public/branding';
    }

    public function setter($value)
    {
        $image = new ImageResize($value->getRealPath());
        $image->resizeToBestFit(self::MAX_WIDTH, self::MAX_HEIGHT);
        $image->save($value->getRealPath());

        return $value;
    }

    public function authorized(): bool
    {
        return Gate::allows('configure-common-settings');
    }
}
