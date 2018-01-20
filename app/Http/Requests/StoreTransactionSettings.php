<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreTransactionSettings extends FormRequest
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
            'people_results_per_page' => 'required|numeric',
			'transaction_default_value' => 'required|numeric',
			'single_transaction_max_amount' => 'required|numeric',
            'boutique_threshold_days' => 'required|numeric',
            'frequent_visitor_weeks' => 'required|numeric',
            'frequent_visitor_threshold' => 'required|numeric',
        ];
    }
}
