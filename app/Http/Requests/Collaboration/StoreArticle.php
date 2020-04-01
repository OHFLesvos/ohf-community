<?php

namespace App\Http\Requests\Collaboration;

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
                'max:191',
                isset($this->article)
                    ? Rule::unique('kb_articles')->ignore($this->article->id)
                    : Rule::unique('kb_articles'),
            ],
            'slug' => [
                'nullable',
                'alpha_dash',
            ],
            'public' => [
                'boolean',
            ],
            'featured' => [
                'boolean',
            ],
            'content' => [
                'required',
            ],
            'tags' => [
                'nullable',
                'json',
            ],
        ];
    }
}
