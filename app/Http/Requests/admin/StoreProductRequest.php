<?php

namespace App\Http\Requests\admin;

use Illuminate\Foundation\Http\FormRequest;

class StoreProductRequest extends FormRequest
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
            'title_ar' => 'required',
            'title_en' => 'required',
            'description_ar' => 'required|string|max:255',
            'description_en' => 'required|string|max:255',
            'price' => 'required|numeric',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif',
            'images.*' => 'nullable|mimes:png,jpg,jpeg',
            'video' => 'nullable',
        ];
    }
    public function messages()
    {
        return [
            'title_ar.required' => 'الإسم بالعربية مطلوب',
            'title_en.required' => 'الإسم بالانجليزية مطلوب',
            'description_ar.required' => 'الوصف بالعربية مطلوب',
            'description_en.required' => 'الوصف بالانجليزية مطلوب',
            'price.required' => 'السعر مطلوب',
            'price.numeric' => 'السعر يجب ان يكون رقم',
            'image.required' => 'الصورة مطلوبة',
            'image.image' => 'الصورة يجب ان تكون صورة',
            'image.mimes' => 'الصورة يجب ان تكون من نوع jpeg,png,jpg,gif',
            'images.mimes' => 'يجب ان تكون الصورة من نوع png, jpg, jpeg',
            'images.*.mimes' => 'الصور يجب ان تكون من نوع jpeg,png,jpg',
        ];
    }
}
