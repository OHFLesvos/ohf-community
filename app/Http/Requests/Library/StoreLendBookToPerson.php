<?php

namespace App\Http\Requests\Library;

use Illuminate\Foundation\Http\FormRequest;
use App\LibraryLending;

class StoreLendBookToPerson extends FormRequest
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
            'book_id' => [
                'required',
                'exists:library_books,id',
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
            $lending = LibraryLending::where('book_id', $this->book_id)
                ->whereNull('returned_date')
                ->first();
            if ($lending != null) {
                $validator->errors()->add('book_id', __('library.book_already_lent'));
            }
        });
    }
}