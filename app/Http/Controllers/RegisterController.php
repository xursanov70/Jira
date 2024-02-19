<?php

namespace App\Http\Controllers;

use App\Http\Interfaces\RegisterInterface;
use App\Http\Requests\RegisterRequest;
use Illuminate\Http\Request;

class RegisterController extends Controller
{
    public function __construct(protected RegisterInterface $registerInterface)
    {
    }

    public function sendEmail(Request $request)
    {
        return $this->registerInterface->sendEmail($request);
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
