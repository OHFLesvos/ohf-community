<?php

namespace App\Http\Requests\Fundraising;

use Illuminate\Foundation\Http\FormRequest;

class StoreComment extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'content' => [
                'required',
                'filled',
            ],
        ];
    }
}
