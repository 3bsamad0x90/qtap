<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Validator;

class CardUserRequest extends FormRequest
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
            'full_name' => 'required|string',
            'email' => 'required|email|unique:cards,email',
            'phone' => 'required|regex:/^([0-9\s\-\+\(\)]*)$/',
            'website' => 'required|string|url',
            'language' => 'required|in:ar,en',
            'type' => 'required|string',
            'theme' => 'required|string',
        ];
    }

    public function messages()
    {
        return [

            'email.required' => 'Title is required',
            'language.required' => 'Body is required'
        ];

    }


}
