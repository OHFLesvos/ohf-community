<?php

namespace App\Settings\Library;

use App\Settings\BaseSettingsField;
use Illuminate\Support\Facades\Gate;

class MaxBooksPerPerson extends BaseSettingsField
{
    public function section(): string
    {
        return 'library';
    }

    public function labelKey(): string
    {
        return 'library.max_amount_of_books_person_can_lend';
    }

    public function defaultValue()
    {
        return null;
    }

    public function formType(): string
    {
        return 'number';
    }

    public function formArgs(): ?array
    {
        return [
            'min' => 1,
        ];
    }

    public function formValidate(): ?array
    {
        return [
            'nullable',
            'numeric',
            'min:1',
        ];
    }

    public function authorized(): bool
    {
        return Gate::allows('configure-library');
    }
}
