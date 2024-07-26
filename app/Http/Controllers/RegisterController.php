<?php

namespace App\Http\Controllers;

use App\Http\Interfaces\RegisterInterface;
use App\Http\Requests\ConfirmCodeRequest;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Http\Requests\SendEmailRequest;
use App\Http\Requests\UpdatePasswordRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Models\SendTask;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class RegisterController extends Controller
{
    public function __construct(protected RegisterInterface $registerInterface)
    {
    }

    public function sendEmail(SendEmailRequest $request)
    {
        return $this->registerInterface->sendEmail($request);
    }

    public function userRegister(RegisterRequest $request)
    {
        return $this->registerInterface->userRegister($request);
    }

    public function confirmCode(ConfirmCodeRequest $request)
    {
        return $this->registerInterface->confirmCode($request);
    }

    public function updateUser(UpdateUserRequest $request)
    {
        return $this->registerInterface->updateUser($request);
    }
    public function userLogin(LoginRequest $request)
    {
        return $this->registerInterface->userLogin($request);
    }
    public function getUsers()
    {
        return $this->registerInterface->getUsers();
    }
    public function authUser()
    {
        return $this->registerInterface->authUser();
    }

    public function searchUser()
    {
        return $this->registerInterface->searchUser();
    }

    public function changeSendEmail()
    {
        return $this->registerInterface->changeSendEmail();
    }

    public function updatePassword(UpdatePasswordRequest $request){
        
        $user = User::find(auth()->user()->id);

        if (!Hash::check($request->input('password'), $user->password)) {
            return response()->json([
                'status' => 'error',
                'message' => "Joriy parol noto'g'ri kiritildi",
            ], 422);
        }
    
        $user->password = Hash::make($request->input('new_password'));
        $user->save();
    
        return response()->json([
            'status' => 'success',
            'message' => "Parol muvaffaqqiyatli o'zgartirildi!",
        ], 200);
    }

    public function test(){
        return SendTask::get();
    }
}
