<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SendEmailRequest extends FormRequest
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
            "email" => "required|email:rfc,dns|max:50|unique:users,email,except,id",
        ];
    }

    public function messages()
    {
        return [
            "email.required" => "email kiriting",
            "email.email" => "Email manzilingiz to'g'ri formatda emas",
            "email.dns" => "Email manzilingizning domeni mavjud emas",
            "email.unique" => "Siz oldin kiritilgan email address kiritdingiz",
            "email.max" => "email belgilangan miqdordan ko'p kiritildi", 
        ];
    }
}
