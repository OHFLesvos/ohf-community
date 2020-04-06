<?php

namespace App\Settings\Common;

use App\Settings\BaseSettingsField;
use Gumlet\ImageResize;
use Illuminate\Support\Facades\Auth;

class LogoFile extends BaseSettingsField
{
    public function section(): string
    {
        return 'common';
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

    public function formHelp(): ?string
    {
        return __('app.image_will_be_visible_e_g_on_login_screen');
    }

    public function formFilePath(): ?string
    {
        return 'public/common';
    }

    public function setter($value)
    {
        $image = new ImageResize($value->getRealPath());
        $image->resizeToBestFit(1000, 1000, true);
        $image->save($value->getRealPath());
        return $value;
    }

    public function authorized(): bool
    {
        return Auth::user()->isSuperAdmin(); // TODO
    }
}
