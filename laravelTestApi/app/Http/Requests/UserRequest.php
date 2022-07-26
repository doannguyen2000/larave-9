<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;

class UserRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name' => 'required|min:2',
            'email' => 'required|email|unique:user,email',
            'password' => 'required|min:6',
            'avatar' => 'required|image',
            'role' => 'required',
        ];
    }
}
