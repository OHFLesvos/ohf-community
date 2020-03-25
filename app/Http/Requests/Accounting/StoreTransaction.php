<?php

namespace App\Http\Requests\Accounting;

use App\Http\Controllers\Accounting\AccountingSettingsController;
use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Setting;

class StoreTransaction extends FormRequest
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
                Setting::has(AccountingSettingsController::CATEGORIES_SETTING_KEY) ? Rule::in(Setting::get(AccountingSettingsController::CATEGORIES_SETTING_KEY)) : null,
            ],
            'project' => [
                'nullable',
                Setting::has(AccountingSettingsController::PROJECTS_SETTING_KEY) ? Rule::in(Setting::get(AccountingSettingsController::PROJECTS_SETTING_KEY)) : null,
            ],
            'description' => [
                'required',
            ],
        ];
    }
}
