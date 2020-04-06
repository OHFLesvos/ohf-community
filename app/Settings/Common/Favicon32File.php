<?php

namespace App\Settings\Common;

class Favicon32File extends FaviconFile
{
    public function dimension(): int
    {
        return 32;
    }

    public function label(): string
    {
        return __('app.favicon_32');
    }

    public function formHelp(): ?string
    {
        return __('app.favicon_32_explanation');
    }
}
