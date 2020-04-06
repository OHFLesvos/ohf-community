<?php

namespace App\Settings;

interface SettingsField
{
    public function section(): string;

    public function label(): string;

    public function defaultValue();

    public function formType(): string;

    public function formArgs(): ?array;

    public function formList(): ?array;

    public function formValidate(): ?array;

    public function formHelp(): ?string;

    public function formPlaceholder(): ?string;

    public function includePre(); // TODO PHP 8: : ?string|array

    public function includePost(); // TODO PHP 8: : ?string|array

    public function setter($value);

    public function getter($value);

    public function authorized(): bool;
}
