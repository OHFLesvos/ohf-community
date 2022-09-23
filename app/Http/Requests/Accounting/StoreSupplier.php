<?php

namespace App\Http\Requests\Accounting;

use Illuminate\Foundation\Http\FormRequest;

class StoreSupplier extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => [
                'required',
            ],
            'email' => [
                'nullable',
                'email',
            ],
            'website' => [
                'nullable',
                'url',
            ],
            'iban' => [
                'nullable',
                'iban',
            ],
        ];
    }
}
