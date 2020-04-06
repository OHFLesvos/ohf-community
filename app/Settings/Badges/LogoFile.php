<?php

namespace App\Settings\Badges;

use App\Settings\BaseSettingsField;
use Gumlet\ImageResize;
use Illuminate\Support\Facades\Gate;

class LogoFile extends BaseSettingsField
{
    private const MAX_WIDTH = 800;
    private const MAX_HEIGHT = 800;

    public function section(): string
    {
        return 'badges';
    }

    public function label(): string
    {
        return __('app.logo');
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
        return 'public/badges';
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
        return Gate::allows('create-badges');
    }
}
