<?php

namespace App\Http\Controllers;

use App\Http\Interfaces\RegisterInterface;
use App\Http\Requests\ConfirmCodeRequest;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Http\Requests\SendEmailRequest;
use App\Http\Requests\UpdateUserRequest;

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

    public function changeSendEmail(){
        return $this->registerInterface->changeSendEmail();
    }
    public function updateUser(UpdateUserRequest $request)
    {
        return $this->registerInterface->updateUser($request);
    }
}
