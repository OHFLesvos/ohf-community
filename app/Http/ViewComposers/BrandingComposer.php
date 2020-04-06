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
        $signet_url = Setting::has('common.signet_file') ? Storage::url(Setting::get('common.signet_file')) : null;
        $view->with('signet_url', $signet_url);
    }
}
