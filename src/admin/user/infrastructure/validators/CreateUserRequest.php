<?php

namespace Src\admin\user\infrastructure\validators;

use Illuminate\Foundation\Http\FormRequest;

class CreateUserRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name' => 'required|min:3|max:255',
            'email' => 'required|email|min:3|max:255',
            'password' => 'required|min:8|max:255',
            'role' => 'sometimes|string|in:super_admin,admin,editor',
        ];
    }
}
