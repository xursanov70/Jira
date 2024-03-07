<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ConfirmCodeRequest extends FormRequest
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
            "code" => "required|string|size:5",
        ];
    }

    public function messages()
    {
        return [
            "code.required" => "kod kiriting",
            "code.string" => "raqam  kiriting",
            "code.size" => "besh xonali kod kiriting",    
        ];
    }
}
