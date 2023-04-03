<?php

namespace App\Http\Requests\admin;

use Illuminate\Foundation\Http\FormRequest;

class StoreBook extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'photo' => 'nullable|image|max:1024',
            'backPage' => 'nullable|image|max:1024',
            'name_ar' => 'required',
            'writer_id' => 'required',
            'pages' => 'required',
            'weight' => 'required',
            'Dimensions' => 'required',
            'hard_price' => 'required_with:hardCopy',
            'pdf_price' => 'required_with:pdfCopy'
        ];
    }
}
