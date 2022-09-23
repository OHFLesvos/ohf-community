<?php

namespace App\Http\Requests\Fundraising;

use Illuminate\Foundation\Http\FormRequest;

class StoreDonor extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'first_name' => [
                'required_without_all:last_name,company',
            ],
            'last_name' => [
                'required_without_all:first_name,company',
            ],
            'company' => [
                'required_without_all:first_name,last_name',
            ],
            'email' => [
                'nullable',
                'email',
            ],
            'country_name' => [
                'nullable',
                'country_name',
            ],
            'language' => [
                'nullable',
                'language_name',
            ],
        ];
    }
}
