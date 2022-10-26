<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ResedentialRequest extends FormRequest
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
            'name' => ['required', 'string', 'max:45'],
            'unit_number' => ['required', 'integer'],
            'type' => ['required','string', 'max:20'],
            'description' => ['required', 'string', 'max:200'],
            'image' => ['required','image', 'max:2048' ,'mimes:jpg,jpeg,png,svg'],
        ];
    }
}
