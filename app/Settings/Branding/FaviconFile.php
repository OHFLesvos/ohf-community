<?php

namespace App\Settings\Branding;

use App\Settings\BaseSettingsField;
use Gumlet\ImageResize;
use Illuminate\Support\Facades\Gate;

abstract class FaviconFile extends BaseSettingsField
{
    abstract public function dimension(): int;

    public function section(): string
    {
        return 'branding';
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
            'accept' => 'image/png',
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
        return 'public/branding';
    }

    public function setter($value)
    {
        $dimension = $this->dimension();
        $image = new ImageResize($value->getRealPath());
        $image->resizeToBestFit($dimension, $dimension, true);
        $image->save($value->getRealPath());

        return $value;
    }

    public function authorized(): bool
    {
        return Gate::allows('configure-common-settings');
    }
}
