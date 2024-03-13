<?php

namespace App\Http\Repositories;

use App\Http\Interfaces\RegisterInterface;
use App\Http\Requests\ConfirmCodeRequest;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Http\Requests\SendEmailRequest;
use App\Http\Resources\UserResource;
use App\Mail\Message;
use App\Models\ConfirmCode;
use App\Models\User;
use Carbon\Carbon;
use DateTime;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;



class RegisterRepository implements RegisterInterface
{
    public function sendEmail(SendEmailRequest $request)
    {
        try {
            $rand = rand(10000, 99999);
            Mail::to($request->email)->send(new Message($rand));

            ConfirmCode::create([
                'code' => $rand,
                'email' => $request->email,
            ]);
            return response()->json(["message" => "Email pochtangizga kod jo'natildi"], 200);
        } catch (\Exception $exception) {
            return response()->json([
                "message" => "Email yuborishda xatolik yuz berdi",
                "error" => $exception->getMessage(),
                "line" => $exception->getLine(),
                "file" => $exception->getFile()
            ]);
        }
    }


    public function confirmCode(ConfirmCodeRequest $request)
    {
        $confirm_code = ConfirmCode::where('email', $request->email)
            ->where('active', false)
            ->orderBy('id', 'desc')->first();

        if (!$confirm_code) {
            return response()->json(["message" => "Noto'g'ri ma'lumot kiritdingiz!"], 401);
        }
        if ($confirm_code->code == $request->code) {

            $create = new DateTime(Carbon::parse($confirm_code->created_at));
            $now = new DateTime(Carbon::now());
            $secund = $now->getTimestamp() - $create->getTimestamp();

            if ($secund >= 120) {
                return response()->json(["message" => "Kod kiritish vaqti tugagan!"], 401);
            }
            $confirm_code->active = true;
            $confirm_code->save();
            return response()->json(["message" => "Siz kiritgan kod tasdiqlandi!"], 200);
        } else {
            return response()->json(["message" => "Noto'g'ri kod kiritdingiz!"], 401);
        }
    }

    public function userRegister(RegisterRequest $request)
    {
        try {
            $code = ConfirmCode::where('email', $request->email)
                ->where('active', true)
                ->orderBy('id', 'desc')->first();

            if (!$code)
                return response()->json(["message" => "Siz kod tasdiqlamagansiz!"], 401);

            $user =  User::create([
                'fullname' => $request->fullname,
                'username' => $request->username,
                'email' => $request->email,
                'phone' => $request->phone,
                'password' => Hash::make($request->password),
            ]);
            $code->delete();
            $token = $user->createToken('auth-token')->plainTextToken;
            return response()->json(["message" => "Ro'yxatdan muvaffaqqiyatli o'tdingiz!", "token" => $token], 200);
        } catch (\Exception $exception) {
            return response()->json([
                "message" => "Ro'yxatdan o'tishda xatolik yuz berdi",
                "error" => $exception->getMessage(),
                "line" => $exception->getLine(),
                "file" => $exception->getFile()
            ]);
        }
    }


    public function userLogin(LoginRequest $request)
    {
        $login = [
            'username' => $request->username,
            'password' => $request->password

        ];

        if (Auth::attempt($login)) {
            $user = $request->user();
            $token = $user->createToken('auth-token')->plainTextToken;
            return response()->json(['token' => $token, 'success' => true], 200);
        } else {
            return response()->json(['error' => 'Invalid credentials'], 401);
        }
    }

    public function getUsers()
    {
        return UserResource::collection(User::paginate(15));
    }

    public function authUser()
    {
        return new UserResource(Auth::user());
    }

    public function searchUser()
    {
        $search = request('search');
        $auth = Auth::user()->id;

        $user = User::where('id', '!=', $auth)
            ->when($search, function ($query) use ($search) {
                $query->where('username', 'like', "%$search%")
                    ->orWhere('fullname', 'like', "%$search%")
                    ->orWhere('phone', 'like', "%$search%")
                    ->orWhere('email', 'like', "%$search%");
            })
            ->orderBy('users.id', 'asc')
            ->paginate(30);
        return UserResource::collection($user);
    }
}
