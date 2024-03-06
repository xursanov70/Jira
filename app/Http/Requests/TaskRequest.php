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
            'original_task' => 'required|date|after_or_equal:' . now('Asia/Tashkent')->format('Y-m-d') . '|before:2050-01-01 00:00:00',
            'high' =>  'required|in:High,Medium,Low',
            "description" => "required|max:255",
            "task_name" => "required|max:50",
        ];
    }

    public function messages()
    {
        return [
            "category_name.required" => "category nomini kiritng",
            "category_name.in" => "category nomini to'g'ri kiritng",
            "original_task.required" => "task uchun vaqt belgilang",
            "original_task.after_or_equal" => "Kiritilgan original task vaqti hozirgi vaqtdan oldin bo'lmasligi kerak",
            "original_task.date" => "Task vaqtini to'g'ri kiritng",
            "original_task.before" => "Task uchun ko'p vaqt belgiladingiz",
            "high.required" => "high kiritng",
            "high.in" => "Zarurlik darajasini to'g'ri kiriting",
            "task_name.required" => "Task nomini kiritng",
            "task_name.max" => "task nomi  haddan ziyod ko'p kiritildi",
            "description.required" => "task haqida ma'lumot kiritng",
            "description.max" => "task haqida ma'lumot haddan ziyod ko'p kiritildi",
        ];
    }
}
