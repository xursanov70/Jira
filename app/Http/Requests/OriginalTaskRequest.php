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
            'original_task' => 'required|date|after_or_equal:' . now('Asia/Tashkent')->format('Y-m-d') . '|before:2050-01-01 00:00:00',
        ];
    }

    public function messages()
    {
        return [
            "original_task.required" => "task uchun vaqt belgilang",
            "original_task.after_or_equal" => "Kiritilgan original task vaqti hozirgi vaqtdan oldin bo'lmasligi kerak",
            "original_task.date" => "Task vaqtini to'g'ri kiritng",
            "original_task.before" => "Task uchun ko'p vaqt belgiladingiz",
            
        ];
    }
}
