<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SendTaskRequest extends FormRequest
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
            "partner_id" => "required",
            "category_name" => "required",
            "original_task" => "required",
            "high" => "required",
            "description" => "required",
            "task_name" => "required",
        ];
    }

    public function messages()
    {
        return [
            "category_name.required" => "category nomini kiritng",
            "partner_id.required" => "kim uchunligini  kiritng",
            "original_task.required" => "original_task kiritng",
            "high.required" => "high kiritng",
            "task_name.required" => "Task nomini kiritng",
            "description.required" => "task haqida ma'lumot kiritng",
        ];
    }
}
