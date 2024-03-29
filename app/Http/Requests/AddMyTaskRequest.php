<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AddMyTaskRequest extends FormRequest
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
            "send_decline_task_id" => "required|integer",
        ];
    }

    public function messages()
    {
        return [
            "send_decline_task_id.required" => "qabul qilinmagan task id raqamini kiriting",
            "send_decline_task_id.integer" => "raqam  kiriting",

        ];
    }
}
