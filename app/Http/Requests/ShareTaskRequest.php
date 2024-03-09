<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ShareTaskRequest extends FormRequest
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
            "task_id" => "required|string",
            "user_id" => "required|string",
        ];
    }

    public function messages()
    {
        return [
            "task_id.required" => "Taskingiz id raqamini kiriting",
            "partner_id.string" => "taskingizni  id  raqamini to'gri  kiriting",
            "partner_id.string" => "sherigingizni  id  raqamini to'gri  kiriting",
            "partner_id.required" => "yuborayotgan sherigingizni id raqamini kiriting",

        ];
    }
}
