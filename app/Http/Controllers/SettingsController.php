<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Config;

abstract class SettingsController extends Controller
{
    protected abstract function getSections();

    protected abstract function getSettings();

    protected abstract function getRedirectRouteName();

    protected abstract function getUpdateRouteName();

    /**
     * View for configuring settings.
     * 
     * @return \Illuminate\Http\Response
     */
    function edit() {
        return view('settings', [
            'route' => $this->getUpdateRouteName(),
            'sections' => $this->getSections(),
            'fields' => collect($this->getSettings())
                ->mapWithKeys(function($e, $k){ return [ 
                    str_slug($k) => [
                        'value' => \Setting::get($k, $e['default']),
                        'type' => $e['form_type'],
                        'label' => __($e['label_key']),
                        'section' => $e['section'],
                        'args' => $e['form_args'],
                        'include_pre' => $e['include_pre'] ?? null,
                        'include_post' => $e['include_post'] ?? null,
                    ]
                ]; }),
        ]);
    }

    /**
     * Update settings
     * 
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    function update(Request $request) {

        // Validate
        $request->validate(
            collect($this->getSettings())
                ->filter(function($f){ 
                    return isset($f['form_validate']);
                })
                ->mapWithKeys(function($f, $k) {
                    $rules = is_callable($f['form_validate']) ? $f['form_validate']() : $f['form_validate'];
                    return [str_slug($k) => $rules];
                })
                ->toArray()
        );

        // Update
        foreach($this->getSettings() as $field_key => $field) {
            \Setting::set($field_key, $request->{str_slug($field_key)});        
        }
        \Setting::save();
        
        // Redirect
        return redirect()->route($this->getRedirectRouteName())
            ->with('success', __('app.settings_updated'));
    }
}
