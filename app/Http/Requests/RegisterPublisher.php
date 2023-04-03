<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegisterPublisher extends FormRequest
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
            'name' => 'required',
            'responsible' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required',
            'phone' => 'required|unique:users',
            'mobile' => 'required|unique:users',
            'licenseId' => 'required',
            'licensePhoto' => 'required|image',
            'website' => 'nullable|url'
        ];
    }
}
