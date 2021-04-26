<?php

namespace App\Settings\Branding;

class Favicon32File extends FaviconFile
{
    public function dimension(): int
    {
        return 32;
    }

    public function label(): string
    {
        return __('Favicon (32 x 32 pixel)');
    }

    public function formHelp(): ?string
    {
        return __('Favicon standard for most desktop browsers');
    }
}
