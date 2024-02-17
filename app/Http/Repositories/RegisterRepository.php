<?php

namespace App\Http\Repositories;

use App\Http\Interfaces\RegisterInterface;
use App\Http\Requests\RegisterRequest;
use App\Http\Resources\UserResource;
use Illuminate\Http\Request;
use App\Mail\Message;
use App\Models\ConfirmCode;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class RegisterRepository implements RegisterInterface
{

    public function userRegister(RegisterRequest $request)
    {

        try {

            $user = User::create([
                'full_name' => $request->full_name,
                'username' => $request->username,
                'phone' => $request->phone,
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ]);

            $rand = rand(10000, 99999);
            Mail::to($request->email)->send(new Message($rand));

            ConfirmCode::create([
                'code' => $rand,
                'email' => $user->email
            ]);
            return response()->json(["message" => "Email pochtangizga kod jo'natildi"]);
        } catch (\Exception $e) {

            return response()->json(['error' => 'Transaction failed: ' . $e->getMessage()], 500);
        }
    }

    public function confirmCode(Request $request)
    {
        $back = request('back');

        $confirm_code = ConfirmCode::select('*')->where('email', $request->email)->first();
        $user = User::select('*')->where('email', $request->email)->first();

        if (!$confirm_code) {
            return response()->json(["message" => "Noto'g'ri email kiritdingiz!"]);
        }

        if ($confirm_code->code != $request->code) {
            if ($back == 'yes') {
                $delete = User::find($user->id);
                $delete->delete();
                $find = ConfirmCode::find($confirm_code->id);
                $find->delete();
                return response()->json(["message" => "Iltimos qaytdan tekshirib ma'lumotlaringizni kiriting!"]);
            } else {
                return response()->json(["message" => "Noto'g'ri kod kiritdingiz!"]);
            }
        } else {
            $find = ConfirmCode::find($confirm_code->id);
            $find->delete();

            $token = $user->createToken('auth-token')->plainTextToken;

            return response()->json(["message" => "Siz kiritgan kod tasdiqlandi!", "token" => $token]);
        }
    }

    public function userLogin(Request $request)
    {
        $login = [
            'username' => $request->username,
            'password' => $request->password

        ];

        if (Auth::attempt($login)) {
            $user = $request->user();
            $token = $user->createToken('auth-token')->plainTextToken;
            return response()->json(['token' => $token, 'success' => true]);
        } else {
            return response()->json(['error' => 'Invalid credentials'], 401);
        }
    }

    public function getUsers()
    {
        $get = User::paginate(15);
        return UserResource::collection($get);
    }

    public function authUser()
    {
        $auth = Auth::user();
        return new UserResource($auth);
    }
}
