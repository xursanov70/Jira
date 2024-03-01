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
            "task_id" => "required",
            "user_id" => "required",
            Rule::unique('send_tasks')->where(function ($query){
                return $query->where('partner_id', $this->partner_id);
            })
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
