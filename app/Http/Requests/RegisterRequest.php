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
    public function rules(): array
    {
        return [
            "username" => "required|max:20|regex:/^[A-Za-z0-9\-_]+$/|unique:users,username,except,id",
            "password" => "required|min:6",
            "fullname" => "required|max:30",
            "phone" => "required|size:17|unique:users,phone,except,id",
        ];
    }

    public function messages()
    {
        return [
            "username.required" => "username kiritng",
            "username.unique" => "username oldin kiritilgan",
            "username.max" => "username 20 ta belgidan kam bo'lishi kerak",
            "username.regex" => "yaroqsiz username kiritildi",

            
            "password.required" => "parol kiriting",
            "password.min" => "parol 6 ta belgidan kam bo'lmasligi kerak",
            
            "fullname.max" => "fullname 30 ta belgidan kam bo'lishi kerak",
            "fullname.required" => "to'liq ismingizni kiriting",

            "phone.required" => "telefon raqam kiriting",
            "phone.unique" => "bu raqam oldin kiritilgan",
            "phone.size" => "telefon raqamni to'liq kiriting",
        ];
    }
}
