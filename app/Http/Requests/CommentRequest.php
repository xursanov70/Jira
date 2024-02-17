<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CommentRequest extends FormRequest
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
            "partner_username"=>"required",
            "comment"=>"required",
        ];
    }

    public function messages()
    {
        return [
        "partner_username.required"=>"partner_username kiritng",
        "comment.required"=>"comment kiritng",
        ];
    }
}
