<?php

namespace Modules\Library\Http\Requests;

use Modules\Library\Entities\LibraryLending;

use Illuminate\Foundation\Http\FormRequest;

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
                'required_without:title',
                'exists:library_books,id',
            ],
            'isbn' => [
                'nullable',
                'isbn',
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
                $count = LibraryLending::where('person_id', $this->person->id)->whereNull('returned_date')->count();
                if (\Setting::get('library.max_books_per_person') <= $count) {
                    $validator->errors()->add('book_id', __('library::library.person_cannot_lend_more_than_n_books', ['num' => \Setting::get('library.max_books_per_person')]));
                }
            }

            $lending = LibraryLending::where('book_id', $this->book_id)
                ->whereNull('returned_date')
                ->first();
            if ($lending != null) {
                $validator->errors()->add('book_id', __('library::library.book_already_lent'));
            }
        });
    }
}
