<?php

namespace App\Http\Controllers;

use App\Http\Interfaces\RegisterInterface;
use App\Http\Requests\RegisterRequest;
use App\Http\Resources\UserResource;
use App\Mail\Message;
use App\Models\ConfirmCode;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
    public function __construct(protected RegisterInterface $registerInterface)
    {
    }

    public function userRegister(RegisterRequest $request)
    {
        return $this->registerInterface->userRegister($request);
    }

    public function confirmCode(Request $request)
    {
        return $this->registerInterface->confirmCode($request);
    }
    public function userLogin(Request $request)
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

    public function filterUser()
    {
        return $this->registerInterface->filterUser();
    }
}
