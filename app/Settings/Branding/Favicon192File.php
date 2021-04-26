<?php

namespace App\Settings\Branding;

class Favicon192File extends FaviconFile
{
    public function dimension(): int
    {
        return 192;
    }

    public function label(): string
    {
        return __('Favicon (192 x 192 pixel)');
    }

    public function formHelp(): ?string
    {
        return __('Favicon according to Google Developer Web App Manifest Recommendation');
    }
}
