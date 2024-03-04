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
            // "user_id" => [
            //     "required",
            //     "integer",
            //     "exists:users,id",
            //     Rule::unique('tasks')->where(function ($query) {
            //         return $query->where('id', $this->task_id);
            //     })
            // ]
        ];
    }

    public function messages()
    {
        return [
            "task_id.required" => "task_id kiriting",
            "user_id.required" => "user_id kiriting",

        ];
    }
}
