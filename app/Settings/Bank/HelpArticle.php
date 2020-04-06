<?php

namespace App\Settings\Bank;

use App\Models\Collaboration\WikiArticle;
use App\Settings\BaseSettingsField;
use Illuminate\Support\Facades\Gate;

class HelpArticle extends BaseSettingsField
{
    public function section(): string
    {
        return 'bank';
    }

    public function labelKey(): string
    {
        return 'wiki.help_article';
    }

    public function defaultValue()
    {
        return null;
    }

    public function formType(): string
    {
        return 'select';
    }

    public function formList(): ?array
    {
        return WikiArticle::orderBy('title')
            ->get()
            ->pluck('title', 'id')
            ->toArray();
    }

    public function formValidate(): ?array
    {
        return [
            'nullable',
            'exists:kb_articles,id',
        ];
    }

    public function formPlaceholder(): ?string
    {
        return __('wiki.select_article');
    }

    public function authorized(): bool
    {
        return Gate::allows('configure-bank');
    }
}
