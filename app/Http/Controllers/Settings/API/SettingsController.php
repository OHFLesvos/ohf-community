<?php

namespace App\Http\Controllers\Settings\API;

use App\Http\Controllers\Controller;
use App\Settings\SettingsField;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
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

    private const FIELDS = [
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
        'visitors.living_situations' => \App\Settings\Visitors\VisitorLivingSituations::class,
        'visitors.purposes_of_visit' => \App\Settings\Visitors\VisitorPurposesOfVisit::class,
    ];

    /**
     * Undocumented function
     *
     * @return Collection<string,SettingsField>
     */
    private static function getSettings(): Collection
    {
        return collect(self::FIELDS)
            ->map(fn ($field) => new $field())
            ->filter(fn (object $obj) => $obj instanceof SettingsField)
            ->filter(fn (SettingsField $field) => $field->authorized());
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
            ...self::getFileFieldArgs($field, $value),
        ];
    }

    private static function getFileFieldArgs(SettingsField $field, $value): array
    {
        if ($field->formType() == 'file' && $value !== null) {
            return [
                'file_url' => Storage::url($value),
                'file_is_image' => Str::startsWith(mime_content_type(Storage::path($value)), 'image/'),
            ];
        }

        return [];
    }

    public function fields(): JsonResponse
    {
        $settings = self::getSettings();

        $fields = $settings->mapWithKeys(fn (SettingsField $field, $key) => [
            Str::slug($key) => self::mapSettingsField($field, $key),
        ]);

        return response()
            ->json([
                'sections' => collect(self::getSections())
                    ->filter(fn ($sl, $sk) => $fields->where('section', $sk)->count() > 0)
                    ->toArray(),
                'fields' => $fields,
            ]);
    }

    public function update(Request $request): JsonResponse
    {
        $fields = self::getSettings();

        $request->validate(
            $fields->mapWithKeys(fn (SettingsField $field, $key) => [
                Str::slug($key) => $field->formValidate(),
            ])
                ->filter()
                ->toArray()
        );

        foreach ($fields as $key => $field) {
            self::updateFieldValue($field, $request, $key);
        }
        Setting::save();

        return response()
            ->json([
                'message' => __('Settings have been updated.'),
            ]);
    }

    public function reset(): JsonResponse
    {
        $fields = self::getSettings();

        foreach ($fields as $key => $field) {
            if ($field->formType() == 'file' && Setting::has($key)) {
                Storage::delete(Setting::get($key));
            }
            Setting::forget($key);
        }
        Setting::save();

        return response()
            ->json([
                'message' => __('Settings have been reset.'),
            ]);
    }

    public function resetField(string $key): JsonResponse
    {
        $fields = self::getSettings();
        if (!$fields->keys()->map(fn ($k) => Str::slug($k))->contains($key)) {
            return response()
            ->json([
                'message' => __("Invalid field."),
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $settingsKey = $fields->filter(fn ($v, $k) => Str::slug($k) == $key)->map(fn ($v, $k) => $k)->first();
        $field = $fields[$settingsKey];
        if ($field->formType() == 'file' && Setting::has($settingsKey)) {
            Storage::delete(Setting::get($settingsKey));
        }
        Setting::forget($settingsKey);
        Setting::save();

        return response()
            ->json([
                'message' => __($field->label().' has been reset.'),
            ]);
    }

    private static function updateFieldValue(SettingsField $field, Request $request, string $key): void
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

    private static function handleFileField(SettingsField $field, Request $request, string $key): void
    {
        $req_key = Str::slug($key);
        if ($request->has($req_key.'_delete') || $request->hasFile($req_key)) {
            if (Setting::has($key)) {
                Storage::delete(Setting::get($key));
            }
        }
        if ($request->hasFile($req_key)) {
            $value = $field->setter($request->file($req_key));
            Setting::set($key, $value->store($field->formFilePath()));
        } elseif ($request->has($req_key.'_delete')) {
            Setting::forget($key);
        }
    }

    public function list(): JsonResponse
    {
        return response()
            ->json([
                'accounting.transactions.currency' => Setting::get('accounting.transactions.currency'),
                'accounting.transactions.use_locations' => (bool) Setting::get('accounting.transactions.use_locations', false),
                'accounting.transactions.use_secondary_categories' => (bool) Setting::get('accounting.transactions.use_secondary_categories', false),
                'accounting.transactions.use_cost_centers' => (bool) Setting::get('accounting.transactions.use_cost_centers', false),
                'accounting.transactions.show_intermediate_balances' => (bool) Setting::get('accounting.transactions.show_intermediate_balances', false),
                'visitors.nationalities' => Setting::get('visitors.nationalities', []),
                'visitors.living_situations' => Setting::get('visitors.living_situations', []),
                'visitors.purposes_of_visit' => Setting::get('visitors.purposes_of_visit', []),
                'visitors.retention_days' => config('visitors.retention_days'),
            ]);
    }
}
