<?php

namespace App\Http\Requests\Library;

use Illuminate\Foundation\Http\FormRequest;

class StoreReturnBookFromPerson extends FormRequest
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
}
