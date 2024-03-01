<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class OriginalTaskRequest extends FormRequest
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
            "original_task" => "required",
        ];
    }

    public function messages()
    {
        return [
            "original_task.required" => "tugash vaqti kiriting",
            
        ];
    }
}
