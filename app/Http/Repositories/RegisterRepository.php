<?php

namespace App\Http\Repositories;

use App\Http\Interfaces\RegisterInterface;
use App\Http\Requests\ConfirmCodeRequest;
use App\Http\Requests\RegisterRequest;
use App\Http\Requests\SendEmailRequest;
use App\Http\Resources\UserResource;
use Illuminate\Http\Request;
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

            $user = User::select('*')->where('email', $request->email)->first();
            if ($user) {
                return response()->json(["message" => "Siz oldin kiritilgan email address kiritdingiz!"], 401);
            }
            $rand = rand(10000, 99999);
            Mail::to($request->email)->send(new Message($rand));

            ConfirmCode::create([
                'code' => $rand,
                'email' => $request->email,
            ]);

            return response()->json(["message" => "Email pochtangizga kod jo'natildi"], 200);
        } catch (\Exception $e) {
            throw $e;
        }
    }


    public function confirmCode(ConfirmCodeRequest $request)
    {
        $confirm_code = ConfirmCode::select('*')->where('email', $request->email)->orderBy('id', 'desc')->first();

        if (!$confirm_code) {
            return response()->json(["message" => "Noto'g'ri ma'lumot kiritdingiz!"], 401);
        }
        if ($confirm_code->code == $request->code) {
            $create = new DateTime(Carbon::parse($confirm_code->created_at));
            $now = new DateTime(Carbon::now());

            $secund = $now->getTimestamp() - $create->getTimestamp();
            if ($secund >= 120) {
                $find = ConfirmCode::find($confirm_code->id);
                $find->delete();
                return response()->json(["message" => "Kod kiritish vaqti tugagan!"], 401);
            }

            $find = ConfirmCode::find($confirm_code->id);
            $find->delete();
            return response()->json(["message" => "Siz kiritgan kod tasdiqlandi!"], 200);
        } else {
            return response()->json(["message" => "Noto'g'ri kod kiritdingiz!"], 401);
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
        return response()->json(["message" => "Ro'yxatdan muvaffaqqiyatli o'tdingiz!", "token" => $token], 200);
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
            return response()->json(['token' => $token, 'success' => true], 200);
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

    public function searchUser()
    {
        $search = request('search');
        $auth = Auth::user()->id;

        $user = User::select('id', 'fullname', 'email', 'username', 'phone')
            ->where('id', '!=', $auth)
            ->when($search, function ($query) use ($search) {
                $query->where('username', 'like', "%$search%")
                    ->orWhere('fullname', 'like', "%$search%")
                    ->orWhere('phone', 'like', "%$search%")
                    ->orWhere('email', 'like', "%$search%");
            })
            ->orderBy('users.id', 'asc')
            ->paginate(15);
        return UserResource::collection($user);
    }
}
