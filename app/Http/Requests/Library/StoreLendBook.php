<?php

namespace App\Http\Requests\Library;

use App\Models\Library\LibraryLending;

use Illuminate\Foundation\Http\FormRequest;

class StoreLendBook extends FormRequest
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
            'person_id' => [
                'required',
                'exists:persons,id',
            ]
        ];
    }

    /**
     * Configure the validator instance.
     *
     * @param  \Illuminate\Validation\Validator  $validator
     * @return void
     */
    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            if (\Setting::has('library.max_books_per_person')) {
                $count = LibraryLending::where('person_id', $this->person_id)->whereNull('returned_date')->count();
                if (\Setting::get('library.max_books_per_person') <= $count) {
                    $validator->errors()->add('person_id', __('library::library.person_cannot_lend_more_than_n_books', ['num' => \Setting::get('library.max_books_per_person')]));
                }
            }
        });
    }
}
