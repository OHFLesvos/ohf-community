<?php

namespace Modules\KB\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreArticle extends FormRequest
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
            'title' => [
                'required',
                'max:255',
                isset($this->article) 
                    ? Rule::unique('kb_articles')->ignore($this->article->id) 
                    : Rule::unique('kb_articles'),
            ],
            'content' => 'required',
            'tags' => [
                'nullable',
                'json',
            ]
        ];
    }
}
