<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateUserRequest extends FormRequest
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
<<<<<<< HEAD
            "username" => "max:30|min:2|regex:/^[A-Za-z0-9\-_]+$/|unique:users,username,except,id",
            "password" => "min:6|max:30",
            "fullname" => "max:50|min:2",
            "phone" => "string|size:17|starts_with:+998|unique:users,phone,except,id",
            "send_email" => "boolean",
=======
            "username" => "required|max:30|min:2|regex:/^[A-Za-z0-9\-_]+$/",
            "password" => "required|min:6|max:30",
            "fullname" => "required|max:50|min:2",
            "phone" => "required|string|size:17|starts_with:+998",
            "email" => "required|max:50",
>>>>>>> origin/main
        ];
    }

    public function messages()
    {
        return [
<<<<<<< HEAD
            "username.unique" => "username oldin kiritilgan",
=======
            "username.required" => "username kiritng",
>>>>>>> origin/main
            "username.max" => "username 30 ta belgidan kam bo'lishi kerak",
            "username.min" => "username 2 ta belgidan kam bo'lmasligi kerak",
            "username.regex" => "yaroqsiz username kiritildi",

<<<<<<< HEAD
=======
            
            "password.required" => "parol kiriting",
>>>>>>> origin/main
            "password.min" => "parol 6 ta belgidan kam bo'lmasligi kerak",
            "password.max" => "parol 30 ta belgidan kam bo'lishi kerak",
            
            "fullname.max" => "fullname 50 ta belgidan kam bo'lishi kerak",
            "fullname.min" => "fullname 2 ta belgidan kam bo'lmasligi kerak",
<<<<<<< HEAD

            "phone.unique" => "bu raqam oldin kiritilgan",
            "phone.size" => "telefon raqamni to'liq kiriting",
            "phone.starts_with" => "uzbekistanga tegishli telefon raqami kiriting",
        ];
    }
}
=======
            "fullname.required" => "to'liq ismingizni kiriting",

            "phone.required" => "telefon raqam kiriting",
            "phone.size" => "telefon raqamni to'liq kiriting",
            "phone.starts_with" => "uzbekistanga tegishli telefon raqami kiriting",

            "email.required" => "email kiriting",
            "email.max" => "email belgilangan miqdordan ko'p kiritildi",
        ];
    }
}
>>>>>>> origin/main
