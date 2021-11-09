<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use App\Settings\SettingsField;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Setting;

class SettingsController extends Controller
{
    private static function getSections(): array
    {
        return [
            'branding' => __('Branding'),
            'accounting' => __('Accounting'),
            'badges' => __('Badges'),
            'visitors' => __('Visitors'),
        ];
    }

    private static $fields = [
        'branding.logo_file' => \App\Settings\Branding\LogoFile::class,
        'branding.signet_file' => \App\Settings\Branding\SignetFile::class,
        'branding.favicon_32_file' => \App\Settings\Branding\Favicon32File::class,
        'branding.favicon_180_file' => \App\Settings\Branding\Favicon180File::class,
        'branding.favicon_192_file' => \App\Settings\Branding\Favicon192File::class,
        'accounting.transactions.currency' => \App\Settings\Accounting\TransactionCurrency::class,
        'accounting.transactions.use_secondary_categories' => \App\Settings\Accounting\TransactionSecondaryCategoriesUse::class,
        'accounting.transactions.secondary_categories' => \App\Settings\Accounting\TransactionSecondaryCategories::class,
        'accounting.transactions.use_locations' => \App\Settings\Accounting\TransactionLocationsUse::class,
        'accounting.transactions.locations' => \App\Settings\Accounting\TransactionLocations::class,
        'accounting.transactions.use_cost_centers' => \App\Settings\Accounting\TransactionCostCentersUse::class,
        'accounting.transactions.cost_centers' => \App\Settings\Accounting\TransactionCostCenters::class,
        'accounting.transactions.show_intermediate_balances' => \App\Settings\Accounting\TransactionShowIntermediateBalances::class,
        'badges.logo_file' => \App\Settings\Badges\LogoFile::class,
        'visitors.nationalities' => \App\Settings\Visitors\VisitorNationalities::class,
    ];

    private static function getSettings(): Collection
    {
        return collect(self::$fields)
            ->map(fn ($field) => new $field())
            ->filter(fn (SettingsField $field) => $field->authorized());
    }

    public function edit()
    {
        $settings = self::getSettings();

        if ($settings->isEmpty()) {
            return view('settings.empty');
        }

        $fields = $settings->mapWithKeys(fn (SettingsField $field, $key) => [
            Str::slug($key) => self::mapSettingsField($field, $key),
        ]);
        return view('settings.edit', [
            'sections' => collect(self::getSections())
                ->filter(fn ($sl, $sk) => $fields->where('section', $sk)->count() > 0)
                ->toArray(),
            'fields' => $fields,
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
            foreach ($fields as $key => $field) {
                if ($field->formType() == 'file' && Setting::has($key)) {
                    Storage::delete(Setting::get($key));
                }
                Setting::forget($key);
            }
            Setting::save();

            return redirect()
                ->route('settings.edit')
                ->with('success', __('Settings has been reset.'));
        }

        // Validate
        $request->validate(
            $fields->mapWithKeys(fn (SettingsField $field, $key) => [
                Str::slug($key) => $field->formValidate(),
            ])
                ->filter()
                ->toArray()
        );

        // Update
        foreach ($fields as $key => $field) {
            self::updateFieldValue($field, $request, $key);
        }
        Setting::save();

        return redirect()
            ->route('settings.edit')
            ->with('success', __('Settings have been updated.'));
    }

    private static function updateFieldValue(SettingsField $field, Request $request, string $key)
    {
        if ($field->formType() == 'file') {
            self::handleFileField($field, $request, $key);
        } else {
            $value = $request->input(Str::slug($key));
            if ($value !== null) {
                $value = $field->setter($value);
                Setting::set($key, $value);
            } else {
                Setting::forget($key);
            }
        }
    }

    private static function handleFileField(SettingsField $field, Request $request, string $key)
    {
        $req_key = Str::slug($key);
        if ($request->has($req_key . '_delete') || $request->hasFile($req_key)) {
            if (Setting::has($key)) {
                Storage::delete(Setting::get($key));
            }
        }
        if ($request->hasFile($req_key)) {
            $value = $field->setter($request->file($req_key));
            Setting::set($key, $value->store($field->formFilePath()));
        } elseif ($request->has($req_key . '_delete')) {
            Setting::forget($key);
        }
    }
}
