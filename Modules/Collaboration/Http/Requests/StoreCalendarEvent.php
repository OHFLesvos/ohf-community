<?php

namespace Modules\Collaboration\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCalendarEvent extends FormRequest
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
            'start' => 'required|date',
            'end' => 'nullable|date',
            'title' => 'required|string',
            'resourceId' => 'required|exists:calendar_resources,id',
        ];
    }
}
