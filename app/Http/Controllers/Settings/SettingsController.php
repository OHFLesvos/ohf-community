<?php

namespace App\Http\Controllers\Settings;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

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
                        'section' => $e['section'] ?? null,
                        'args' => $e['form_args'] ?? null,
                        'include_pre' => $e['include_pre'] ?? null,
                        'include_post' => $e['include_post'] ?? null,
                        'list' => $e['form_list'] ?? null,
                        'help' => $e['form_help'] ?? null,
                        'placeholder' => $e['form_placeholder'] ?? null,
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
            $value = $request->{str_slug($field_key)};
            if ($value !== null) {
                \Setting::set($field_key, $value);
            } else {
                \Setting::forget($field_key);
            }
        }
        \Setting::save();
        
        // Redirect
        return redirect()->route($this->getRedirectRouteName())
            ->with('success', __('app.settings_updated'));
    }
}
