<?php

namespace App\Http\Interfaces;

use App\Http\Requests\RegisterRequest;
use App\Http\Requests\SendEmailRequest;
use Illuminate\Http\Request;

interface RegisterInterface
{
    function sendEmail(SendEmailRequest $request);
    function userRegister(RegisterRequest $request);
    function confirmCode(Request $request);
    function userLogin(Request $request);
    function getUsers();
    function authUser();
    function searchUser();
}
