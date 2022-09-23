<?php

namespace App\Http\ViewComposers;

use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Setting;

class BrandingComposer
{
    public function compose(View $view): void
    {
        $view->with('logo_url', self::fileUrlFromSettings('branding.logo_file'));
        $view->with('signet_url', self::fileUrlFromSettings('branding.signet_file'));
        $view->with('favicon_32_url', self::fileUrlFromSettings('branding.favicon_32_file'));
        $view->with('favicon_180_url', self::fileUrlFromSettings('branding.favicon_180_file'));
        $view->with('favicon_192_url', self::fileUrlFromSettings('branding.favicon_192_file'));
    }

    /**
     * Bind data to the view.
     */
    private static function fileUrlFromSettings(string $key): ?string
    {
        return Setting::has($key) ? Storage::url(Setting::get($key)) : null;
    }
}
