<?php

namespace App\Http\Requests\Accounting;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreProject extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
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
            'parent_id' => [
                'nullable',
                Rule::exists('accounting_projects', 'id'),
                isset($this->project) ? Rule::notIn($this->project->id) : null,
            ]
        ];
    }
}
