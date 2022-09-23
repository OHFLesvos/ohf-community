<?php

namespace App\Http\Requests\CommunityVolunteers;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Unique;

class StoreResponsibility extends FormRequest
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
                Rule::unique('community_volunteer_responsibilities')
                    ->when(isset($this->responsibility), fn (Unique $rule) => $rule->ignore($this->responsibility->id)),
            ],
            'capacity' => [
                'nullable',
                'numeric',
                'min:1',
            ],
            'available' => [
                'boolean',
            ],
        ];
    }
}
