<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DeleteForMyTaskRequest extends FormRequest
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
            "title" => "required|max:80|min:10",
        ];
    }

    public function messages()
    {
        return [
            "title.required" => "Nima uchun bu taskni qabul qilmaganingizni yozib qoldiring",
            "title.max" =>  "matn 80 ta belgidan kam bo'lishligi kerak",
            "title.min" =>  "matn 10 ta belgidan ko'p bolishligi kerak",
        ];
    }
}
