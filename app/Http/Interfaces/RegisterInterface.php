<?php

namespace App\Http\Interfaces;

use App\Http\Requests\RegisterRequest;
use Illuminate\Http\Request;

interface RegisterInterface
{
    function sendEmail(Request $request);
    function userRegister(RegisterRequest $request);
    function confirmCode(Request $request);
    function userLogin(Request $request);
    function getUsers();
    function authUser();
    function filterUser();
}
