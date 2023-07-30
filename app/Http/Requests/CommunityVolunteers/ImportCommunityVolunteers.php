<?php

namespace App\Http\Requests\CommunityVolunteers;

use Illuminate\Foundation\Http\FormRequest;

class ImportCommunityVolunteers extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'file' => 'required|file',
            'map' => 'array',
            'map.*.from' => 'required_with:map.*.to|required_with:map.*.append',
            'map.*.to' => 'required_with:map.*.append',
        ];
    }
}
