<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use App\Models\Bank\CouponType;
use App\Models\Collaboration\WikiArticle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Str;
use Setting;

class SettingsController extends Controller
{
    public const LIBRARY_DEFAULT_LENING_DURATION_DAYS = 14;

    protected function getSections()
    {
        return [
            'accounting' => __('accounting.accounting'),
            'shop' => __('shop.shop'),
            'library' => __('library.library'),
        ];
    }

    protected function getSettings()
    {
        return [
            'accounting.transactions.categories' => [
                'section' => 'accounting',
                'default' => '',
                'form_type' => 'textarea',
                'label_key' => 'app.categories',
                'form_help' => 'Separate items by newline',
                'setter' => fn ($value) => preg_split('/(\s*[,\/|]\s*)|(\s*\n\s*)/', $value),
                'getter' => fn ($value) => implode("\n", $value),
                'authorized' => Gate::allows('configure-accounting'),
            ],
            'accounting.transactions.projects' => [
                'section' => 'accounting',
                'default' => '',
                'form_type' => 'textarea',
                'label_key' => 'app.projects',
                'form_help' => 'Separate items by newline',
                'setter' => fn ($value) => preg_split("/(\s*[,\/|]\s*)|(\s*\n\s*)/", $value),
                'getter' => fn ($value) => implode("\n", $value),
                'authorized' => Gate::allows('configure-accounting'),
            ],
            'shop.coupon_type' => [
                'section' => 'shop',
                'default' => null,
                'form_type' => 'select',
                'form_list' => CouponType::orderBy('name')->where('qr_code_enabled', true)->get()->pluck('name', 'id')->toArray(),
                'form_placeholder' => __('people.select_coupon_type'),
                'form_validate' => 'nullable|exists:coupon_types,id',
                'label_key' => 'coupons.coupon',
                'authorized' => Gate::allows('configure-shop'),
            ],
            'shop.help_article' => [
                'section' => 'shop',
                'default' => null,
                'form_type' => 'select',
                'form_list' => WikiArticle::orderBy('title')->get()->pluck('title', 'id')->toArray(),
                'form_placeholder' => __('wiki.select_article'),
                'form_validate' => 'nullable|exists:kb_articles,id',
                'label_key' => 'wiki.help_article',
                'authorized' => Gate::allows('configure-shop'),
            ],
            'library.default_lening_duration_days' => [
                'section' => 'library',
                'default' => self::LIBRARY_DEFAULT_LENING_DURATION_DAYS,
                'form_type' => 'number',
                'form_args' => [ 'min' => 1 ],
                'form_validate' => 'required|numeric|min:1',
                'label_key' => 'library.default_lening_duration_days_in_days',
                'authorized' => Gate::allows('configure-library'),
            ],
            'library.max_books_per_person' => [
                'section' => 'library',
                'default' => null,
                'form_type' => 'number',
                'form_args' => [ 'min' => 1 ],
                'form_validate' => 'nullable|numeric|min:1',
                'label_key' => 'library.max_amount_of_books_person_can_lend',
                'authorized' => Gate::allows('configure-library'),
            ]
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
