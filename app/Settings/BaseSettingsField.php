<?php

namespace App\Settings;

abstract class BaseSettingsField implements SettingsField
{
    public function formArgs(): ?array
    {
        return null;
    }

    public function formList(): ?array
    {
        return null;
    }

    public function formValidate(): ?array
    {
        return null;
    }

    public function formHelp(): ?string
    {
        return null;
    }

    public function formPlaceholder(): ?string
    {
        return null;
    }

    public function setter($value)
    {
        return $value;
    }

    public function getter($value)
    {
        return $value;
    }
}
