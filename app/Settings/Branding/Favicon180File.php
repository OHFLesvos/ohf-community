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
        return __('Favicon (180 x 180 pixel)');
    }

    public function formHelp(): ?string
    {
        return __('Favicon for iPhone Retina');
    }
}
