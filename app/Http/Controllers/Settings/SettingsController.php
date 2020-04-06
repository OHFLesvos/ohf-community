<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use App\Settings\SettingsField;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Setting;

class SettingsController extends Controller
{
    private static function getSections(): array
    {
        return [
            'accounting' => __('accounting.accounting'),
            'bank' => __('bank.bank'),
            'shop' => __('shop.shop'),
            'library' => __('library.library'),
        ];
    }

    private static $fields = [
        'accounting.transactions.categories' => \App\Settings\Accounting\TransactionCategories::class,
        'accounting.transactions.projects' => \App\Settings\Accounting\TransactionProjects::class,
        'bank.undo_coupon_handout_grace_period' => \App\Settings\Bank\UndoCouponHandoutGracePeriod::class,
        'bank.frequent_visitor_weeks' => \App\Settings\Bank\FrequentVisitorWeeks::class,
        'bank.frequent_visitor_threshold' => \App\Settings\Bank\FrequentVisitorThreshold::class,
        'bank.code_card.label' => \App\Settings\Bank\CodeCardLabel::class,
        'bank.help_article' => \App\Settings\Bank\HelpArticle::class,
        'shop.coupon_type' => \App\Settings\Shop\CouponType::class,
        'shop.help_article' => \App\Settings\Shop\HelpArticle::class,
        'library.default_lending_duration_days' => \App\Settings\Library\DefaultLendingDurationDays::class,
        'library.max_books_per_person' => \App\Settings\Library\MaxBooksPerPerson::class,
    ];

    private static function getSettings(): Collection
    {
        return collect(self::$fields)
            ->map(fn ($field) => new $field())
            ->filter(fn (SettingsField $field) => $field->authorized());
    }

    public function edit()
    {
        $fields = self::getSettings();

        if ($fields->isEmpty()) {
            return view('settings.empty');
        }

        return view('settings.edit', [
            'sections' => self::getSections(),
            'fields' => $fields->mapWithKeys(fn (SettingsField $field, $key) => [
                Str::slug($key) => self::mapSettingsField($field, $key)
            ]),
        ]);
    }

    private static function mapSettingsField(SettingsField $field, string $key): array
    {
        $value = Setting::get($key, $field->defaultValue());
        if ($value != null) {
            $value = $field->getter($value);
        }
        return [
            'value' => $value,
            'type' => $field->formType(),
            'label' => $field->label(),
            'section' => $field->section(),
            'args' => $field->formArgs() ?? null,
            'list' => $field->formList() ?? null,
            'help' => $field->formHelp() ?? null,
            'placeholder' => $field->formPlaceholder() ?? null,
        ];
    }

    public function update(Request $request)
    {
        $fields = self::getSettings();

        // Reset
        if ($request->has('reset')) {
            $fields->keys()
                ->each(function ($field_key) {
                    Setting::forget($field_key);
                });
            Setting::save();

            return redirect()
                ->route('settings.edit')
                ->with('success', __('app.settings_reset'));
        }

        // Validate
        $request->validate(
            $fields->mapWithKeys(fn (SettingsField $field, $key) => [
                    Str::slug($key) => $field->formValidate()
                ])
                ->filter()
                ->toArray()
        );

        // Update
        foreach ($fields as $field_key => $field) {
            $value = $request->{Str::slug($field_key)};
            if ($value !== null) {
                $value = $field->setter($value);
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
