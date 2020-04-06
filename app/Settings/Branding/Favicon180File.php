<?php

namespace App\Settings\Branding;

class Favicon180File extends FaviconFile
{
    public function dimension(): int
    {
        return 180;
    }

    public function label(): string
    {
        return __('app.favicon_180');
    }

    public function formHelp(): ?string
    {
        return __('app.favicon_180_explanation');
    }
}
