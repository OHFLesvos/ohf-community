<?php

namespace App\Http\Requests\Inventory;

use Illuminate\Foundation\Http\FormRequest;

class StoreIngressTransaction extends FormRequest
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
            'quantity.*' => 'numeric|required|min:1',
            'item.*' => 'required|max:191',
            'origin' => 'nullable|max:191',
            'sponsor' => 'nullable|max:191',
        ];
    }
}
