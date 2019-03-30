<?php

namespace Modules\Wiki\Http\Requests;

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
                    ? Rule::unique('wiki_articles')->ignore($this->article->id) 
                    : Rule::unique('wiki_articles'),
            ],
            'content' => 'required',
        ];
    }
}
