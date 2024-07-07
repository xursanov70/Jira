<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;


class UpdatePasswordRequest extends FormRequest
{

    public function rules()
    {
        return [
            'password' => 'required',
            'new_password' => [
                'required',
                'string',
                'min:6',
                'max:30',
                'confirmed',
                'different:password'
            ],
            'confirm' => 'required|same:new_password',
        ];
    }

    protected function prepareForValidation()
{
    $this->merge([
        'new_password_confirmation' => $this->confirm,
    ]);
}

    public function messages()
    {
        return [
            'password.required' => 'Joriy parol kiritilmadi',
            'new_password.min' => 'Yangi parol 6 belgidan kam bo\'lmasligi kerak',
            'new_password.max' => 'Yangi parol 30 belgidan ko\'p bo\'lmasligi kerak',
            'new_password.required' => 'Yangi parol kiritilmadi',
            'new_password.confirmed' => 'Yangi parol tasdiqlanmadi',
            'new_password.different' => 'Yangi parol joriy parol bilan bir xil bo\'lmasligi kerak.',
            'confirm.same' => 'Tasdiqlangan parol yangi parol bilan bir xil bo\'lishligi kerak'
        ];
    }

    
}