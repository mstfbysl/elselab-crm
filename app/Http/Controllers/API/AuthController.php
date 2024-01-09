<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Traits\RespondTrait;
use App\Mail\PasswordRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use Illuminate\Support\Str;

use App\Models\User;
use App\Models\PasswordReset;
use App\Models\SystemSetting;

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class AuthController extends Controller
{
    use RespondTrait;

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);
        


        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            $user = User::where('email', $request->email)->first();
            session()->put('locale', $user->language);

            return $this->respondSuccess([], '', 200);
        }
        
 
        return $this->respondFail('The provided credentials do not match our records!', 401);
    }

    public function password_reset(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email'],
        ]);
        
        $user = User::where('email', $request->email)->first();

        if(!$user){
            return $this->respondFail('The provided credentials do not match our records!', 401);
        }

        // TODO: Send Mail Right Here
        Mail::to($user->email)->send(new PasswordRequest());

        $create = PasswordReset::create([
            'user_id' => $user->id,
            'email' => $user->email,
            'code' => rand(100000, 999999),
            'token' => Str::uuid()
        ]);

        return $this->respondSuccess($create, 'Success', 200);
    }

    public function password_reset_confirm(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'code' => 'required',
            'password' => 'required',
        ]);
        
        $password_reset = PasswordReset::where('token', $request->token)->first();

        if(!$password_reset){
            return $this->respondFail('The provided credentials do not match our records.', 401);
        }

        $user = User::find($password_reset->user_id);
        $user->password = Hash::make($request->password);

        return $this->respondSuccess([], 'Your password is updated, redirecting.', 200);
    }
}