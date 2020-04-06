<?php

namespace App\Settings\Common;

class Favicon192File extends FaviconFile
{
    public function dimension(): int
    {
        return 192;
    }

    public function label(): string
    {
        return __('app.favicon_192');
    }

    public function formHelp(): ?string
    {
        return __('app.favicon_192_explanation');
    }
}
