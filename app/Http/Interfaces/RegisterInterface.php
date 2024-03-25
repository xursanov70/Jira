<?php

namespace App\Http\Interfaces;

use App\Http\Requests\ConfirmCodeRequest;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Http\Requests\SendEmailRequest;
use App\Http\Requests\UpdateUserRequest;

interface RegisterInterface
{
    function sendEmail(SendEmailRequest $request);
    function userRegister(RegisterRequest $request);
    function confirmCode(ConfirmCodeRequest $request);
    function updateUser(UpdateUserRequest $request);
    function userLogin(LoginRequest $request);
    function getUsers();
    function authUser();
    function searchUser();
    function changeSendEmail();
}
