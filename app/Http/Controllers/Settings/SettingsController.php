<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Str;
use Setting;

class SettingsController extends Controller
{
    public const CATEGORIES_SETTING_KEY = 'accounting.transactions.categories';
    public const PROJECTS_SETTING_KEY = 'accounting.transactions.projects';

    protected function getSections()
    {
        return [
            'accounting' => __('accounting.accounting'),
        ];
    }

    protected function getSettings()
    {
        return [
            self::CATEGORIES_SETTING_KEY => [
                'section' => 'accounting',
                'default' => '',
                'form_type' => 'textarea',
                'label_key' => 'app.categories',
                'form_help' => 'Separate items by newline',
                'setter' => fn ($value) => preg_split('/(\s*[,\/|]\s*)|(\s*\n\s*)/', $value),
                'getter' => fn ($value) => implode("\n", $value),
                'authorized' => Gate::allows('configure-accounting'),
            ],
            self::PROJECTS_SETTING_KEY => [
                'section' => 'accounting',
                'default' => '',
                'form_type' => 'textarea',
                'label_key' => 'app.projects',
                'form_help' => 'Separate items by newline',
                'setter' => fn ($value) => preg_split("/(\s*[,\/|]\s*)|(\s*\n\s*)/", $value),
                'getter' => fn ($value) => implode("\n", $value),
                'authorized' => Gate::allows('configure-accounting'),
            ],
        ];
    }

    public function edit()
    {
        return view('settings.edit', [
            'sections' => $this->getSections(),
            'fields' => collect($this->getSettings())
                ->filter()
                ->where('authorized', true)
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

    public function update(Request $request)
    {
        $settings = collect($this->getSettings())
            ->where('authorized', true);

        // Validate
        $request->validate(
            $settings->filter(fn ($field) => isset($field['form_validate']))
                ->mapWithKeys(fn ($field, $key) => [ Str::slug($key) => is_callable($field['form_validate']) ? $field['form_validate']() : $field['form_validate'] ])
                ->toArray()
        );

        // Update
        foreach ($settings as $field_key => $field) {
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

        return redirect()
            ->route('settings.edit')
            ->with('success', __('app.settings_updated'));
    }
}
