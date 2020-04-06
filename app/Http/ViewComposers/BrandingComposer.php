<?php

namespace App\Http\ViewComposers;

use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Setting;

class BrandingComposer
{
    /**
     * Create the composer.
     *
     * @return void
     */
    public function __construct()
    {
    }

    /**
     * Bind data to the view.
     *
     * @param  View  $view
     * @return void
     */
    public function compose(View $view)
    {
        $view->with('logo_url', self::fileUrlFromSettings('branding.logo_file'));
        $view->with('signet_url', self::fileUrlFromSettings('branding.signet_file'));
        $view->with('favicon_32_url', self::fileUrlFromSettings('branding.favicon_32_file'));
        $view->with('favicon_180_url', self::fileUrlFromSettings('branding.favicon_180_file'));
        $view->with('favicon_192_url', self::fileUrlFromSettings('branding.favicon_192_file'));
    }

    private static function fileUrlFromSettings(string $key): ?string
    {
        return Setting::has($key) ? Storage::url(Setting::get($key)) : null;
    }
}
