<?php

namespace App\Http\Requests\Accounting;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

use Carbon\Carbon;

use Setting;

class StoreTransaction extends FormRequest
{
    const CATEGORIES_SETTING_KEY = 'accounting.transactions.categories';
    const PROJECTS_SETTING_KEY = 'accounting.transactions.projects';

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
            'date' => [
                'required',
                'date',
                'before_or_equal:' . Carbon::today(),
            ],
            'type' => [
                'required',
                Rule::in(['income', 'spending']),
            ],
            'amount' => [
                'required',
                'min:0.05',
            ],
            'receipt_picture' => [
                'nullable',
                'image',
            ],
            'beneficiary' => [
                'required',
            ],
            'category' => [
                'required',
                Setting::has(self::CATEGORIES_SETTING_KEY) ? Rule::in(Setting::get(self::CATEGORIES_SETTING_KEY)) : null,
            ],
            'project' => [
                'nullable',
                Setting::has(self::PROJECTS_SETTING_KEY) ? Rule::in(Setting::get(self::PROJECTS_SETTING_KEY)) : null,
            ],
            'description' => [
                'required',
            ],
        ];
    }
}
