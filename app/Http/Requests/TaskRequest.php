<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TaskRequest extends FormRequest
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
            "category_name" => "required|in:Official,Personal",
            'original_task' => 'required|date|after_or_equal:' . date('Y-m-d'). '|before:2050-01-01 00:00:00',
            'high' =>  'required|in:High,Medium,Low',
            "description" => "required|max:255|min:10",
            "task_name" => "required|max:50|min:5",
        ];
    }

    public function messages()
    {
        return [
            "category_name.required" => "category nomini kiritng",
            "category_name.in" => "category nomini to'g'ri kiritng",

            "original_task.required" => "task uchun vaqt belgilang",
            "original_task.after_or_equal" => "Kiritilgan original task vaqti hozirgi vaqtdan oldin bo'lmasligi kerak",
            "original_task.date" => "Task vaqtini to'g'ri kiriting",
            "original_task.before" => "Kiritilgan original task vaqti 2050 yildan past  bo'lishligi kerak",

            "high.required" => "high kiritng",
            "high.in" => "Zarurlik darajasini to'g'ri kiriting",

            "task_name.required" => "Task nomini kiritng",
            "task_name.max" => "task nomi  50 harfdan kam bo'lishligi kerak",
            "task_name.min" => "task nomi  5 ta belgidan kam bo'lmasligi kerak",

            "description.required" => "task haqida ma'lumot kiritng",
            "description.max" => "task haqida ma'lumot 255 harfdan kam bo'lishligi kerak",
            "description.min" => "task haqida ma'lumot 5 harfdan ko'p bo'lishligi kerak",
        ];
    }
}
