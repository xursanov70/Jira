<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SendDeclineTaskRequest extends FormRequest
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
            "decline_task_id" => "required",
            "partner_id" => "required",
        ];
    }

    public function messages()
    {
        return [
            "decline_task_id.required" => "qabul qilinmagan task id raqamini kiriting",
            "partner_id.required" => "yuborayotgan sherigingizni id raqamini kiriting",
            
        ];
    }
}
