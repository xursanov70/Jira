<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DeleteEndTaskRequest extends FormRequest
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
            "end_task_id" => "required|integer",
        ];
    }

    public function messages()
    {
        return [
            "end_task_id.required" => "tugatilgan task id raqamini kiriting",
            "end_task_id.integer" => "raqam  kiriting",

        ];
    }
}
