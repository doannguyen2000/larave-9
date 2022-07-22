<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CouserRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'courseName' => 'required',
            'courseDescription' => 'required',
            'courseContent' => 'required',
            'courseNote' => 'required',
        ];
    }
}
