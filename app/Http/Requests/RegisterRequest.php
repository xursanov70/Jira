<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules()
    {
        return [
            "username" => "required|unique:users,username,except,id",
            "password" => "required",
            "fullname" => "required",
            "phone" => "required|unique:users,phone,except,id",
        ];
    }

    public function messages()
    {
        return [
            "username.required" => "username kiritng",
            "username.unique" => "username oldin kiritilgan",

            "password.required" => "parol kiritng",

            "fullname.required" => "to'liq ismingizni kiriting",

            "phone.required" => "telefon raqam kiriting",
            "phone.unique" => "bu raqam oldin kiritilgan",
            "phone.max" => "telefon raqamni to'liq kiriting",
            "phone.min" => "telefon raqamni to'liq kiriting",
        ];
    }
}
