<?php

namespace App\Settings\Branding;

use App\Settings\BaseSettingsField;
use Gumlet\ImageResize;
use Illuminate\Support\Facades\Gate;

class SignetFile extends BaseSettingsField
{
    private const WIDTH = 200;

    private const HEIGHT = 30;

    public function section(): string
    {
        return 'branding';
    }

    public function label(): string
    {
        return __('Signet');
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
        return __('Size: :x x :y pixel', ['x' => self::WIDTH, 'y' => self::HEIGHT]);
    }

    public function formFilePath(): ?string
    {
        return 'public/branding';
    }

    public function setter($value)
    {
        $image = new ImageResize($value->getRealPath());
        $image->resizeToBestFit(self::WIDTH, self::HEIGHT, true);
        $image->save($value->getRealPath());

        return $value;
    }

    public function authorized(): bool
    {
        return Gate::allows('configure-common-settings');
    }
}
