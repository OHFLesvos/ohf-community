<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Setting;

abstract class SettingsController extends Controller
{
    abstract protected function getSections();

    abstract protected function getSettings();

    abstract protected function getRedirectRouteName();

    abstract protected function getUpdateRouteName();

    /**
     * View for configuring settings.
     *
     * @return \Illuminate\Http\Response
     */
    public function edit()
    {
        return view('settings', [
            'route' => $this->getUpdateRouteName(),
            'sections' => $this->getSections(),
            'fields' => collect($this->getSettings())
                ->filter()
                ->mapWithKeys(fn ($field, $key) => [ Str::slug($key) => self::mapSettingsField($field, $key) ]),
        ]);
    }

    private static function mapSettingsField(array $field, string $key)
    {
        $value = Setting::get($key, $field['default']);
        if ($value != null && isset($field['getter']) && is_callable($field['getter'])) {
            $value = $field['getter']($value);
        }
        return [
            'value' => $value,
            'type' => $field['form_type'],
            'label' => __($field['label_key']),
            'section' => $field['section'] ?? null,
            'args' => $field['form_args'] ?? null,
            'include_pre' => $field['include_pre'] ?? null,
            'include_post' => $field['include_post'] ?? null,
            'list' => $field['form_list'] ?? null,
            'help' => $field['form_help'] ?? null,
            'placeholder' => $field['form_placeholder'] ?? null,
        ];
    }

    /**
     * Update settings
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        // Validate
        $request->validate(
            collect($this->getSettings())
                ->filter(fn ($field) => isset($field['form_validate']))
                ->mapWithKeys(fn ($field, $key) => [ Str::slug($key) => is_callable($field['form_validate']) ? $field['form_validate']() : $field['form_validate'] ])
                ->toArray()
        );

        // Update
        foreach ($this->getSettings() as $field_key => $field) {
            $value = $request->{Str::slug($field_key)};
            if ($value !== null) {
                if (isset($field['setter']) && is_callable($field['setter'])) {
                    $value = $field['setter']($value);
                }
                Setting::set($field_key, $value);
            } else {
                Setting::forget($field_key);
            }
        }
        Setting::save();

        // Redirect
        return redirect()
            ->route($this->getRedirectRouteName())
            ->with('success', __('app.settings_updated'));
    }
}
