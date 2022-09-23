<?php

namespace App\Http\Requests\Accounting;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreProject extends FormRequest
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
                'string',
                'max:191',
            ],
            'description' => [
                'nullable',
            ],
            'enabled' => 'boolean',
            'parent_id' => [
                'nullable',
                Rule::exists('accounting_projects', 'id'),
                isset($this->project) ? Rule::notIn($this->project->id) : null,
            ],
        ];
    }
}
