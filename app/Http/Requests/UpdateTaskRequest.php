<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateTaskRequest extends FormRequest
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
            "partner_id" => "required|integer",
            "category_name" => "required|in:Official,Personal",
            'original_task' => 'required|date|after_or_equal:' . date('Y-m-d') . '|before:2100-01-01 00:00:00',
            'high' => 'required|in:High,Medium,Low',
            "description" => "required|max:300",
            "task_name" => "required|max:80",
        ];
    }

    public function messages()
    {
        return [
            "category_name.required" => "category nomini kiritng",
            "category_name.in" => "category nomini to'g'ri kiritng",
            
            "partner_id.required" => "kim uchunligini  kiritng",
            "partner_id.integer" => "sherigingizni  id  raqamini to'gri  kiriting",

            "original_task.required" => "task uchun vaqt belgilang",
            "original_task.after_or_equal" => "Kiritilgan original task vaqti hozirgi vaqtdan oldin bo'lmasligi kerak",
            "original_task.before" => "Kiritilgan original task vaqti 2100 yildan past  bo'lishligi kerak",
            "original_task.date" => "Task vaqtini to'g'ri kiritng",

            "high.required" => "high kiritng",
            "high.in" => "Zarurlik darajasini to'g'ri kiriting",

            "task_name.required" => "Task nomini kiritng",
            "task_name.max" => "task nomi  80 harfdan kam bo'lishligi kerak",
            "task_name.min" => "task nomi  5 ta belgidan kam bo'lmasligi kerak",

            "description.required" => "task haqida ma'lumot kiritng",
            "description.max" => "task haqida ma'lumot 300 harfdan kam bo'lishligi kerak", 
        ];
    }
}
