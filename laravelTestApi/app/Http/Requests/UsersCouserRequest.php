<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UsersCouserRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'userID' => 'required|exists:user,id',
            'courseID' => 'required|exists:course,id',
            'status' => 'required'
        ];
    }

    public function messages()
    {
        return [
            'userID.exists' => 'User doesnot exists',
            'courseID.exists' => 'Course doesnot exists',
        ];
    }
}
