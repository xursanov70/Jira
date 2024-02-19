<?php

namespace App\Http\Controllers;

use App\Http\Interfaces\RegisterInterface;
use App\Http\Requests\ConfirmCodeRequest;
use App\Http\Requests\RegisterRequest;
use App\Http\Resources\UserResource;
use App\Mail\Message;
use App\Models\ConfirmCode;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
    public function __construct(protected RegisterInterface $registerInterface)
    {
    }

    // public function userRegister(RegisterRequest $request)
    // {
    //     return $this->registerInterface->userRegister($request);
    // }

    // public function confirmCode(Request $request)
    // {
    //     return $this->registerInterface->confirmCode($request);
    // }
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

    public function sendEmail(Request $request)
    {

        $rand = rand(10000, 99999);
        Mail::to($request->email)->send(new Message($rand));

        ConfirmCode::create([
            'code' => $rand,
            'email' => $request->email
        ]);
        return response()->json(["message" => "Email pochtangizga kod jo'natildi"]);
    }

    public function confirmCode(Request $request)
    {
        $confirm_code = ConfirmCode::select('*')->where('email', $request->email)->orderBy('id', 'desc')->first();

        if (!$confirm_code) {
            return response()->json(["message" => "Noto'g'ri email kiritdingiz!"]);
        }
        if ($confirm_code->code == $request->code) {
            $find = ConfirmCode::find($confirm_code->id);
            $find->delete();
            return response()->json(["message" => "Siz kiritgan kod tasdiqlandi!"]);
        } else {
            return response()->json(["message" => "Noto'g'ri kod kiritdingiz!"]);
        }
    }

    public function userRegister(RegisterRequest $request)
    {
        $user =  User::create([
            'fullname' => $request->fullname,
            'username' => $request->username,
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => Hash::make($request->password),
        ]);
        $token = $user->createToken('auth-token')->plainTextToken;
        return response()->json(["message" => "Ro'yxatdan muvaffaqqiyatli o'tdingiz!", "token" => $token]);
    }
}
