<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;

use App\Http\Controllers\Controller;
use App\Http\Traits\RespondTrait;

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

use App\Models\User;
use App\Models\SystemFile;

class AccountController extends Controller
{
    use RespondTrait;

    public function detail()
    {
        $user = Auth::user();

        if(!$user){
            return $this->respondFail('Unauthorized access!', 401);
        }

        $user->permissions = $user->role->permissions;

        return $this->respondSuccess($user);
    }

    public function save_details(Request $request)
    {
        $request->validate([
            'name_surname' => 'required',
            'email' => 'required',
        ]);
        
        $user = Auth::user();

        if(!$user){
            return $this->respondFail('An error occured!', 500);
        }

        if($user->email != $request->email){
            $check_exist = User::where('email', $request->email)->first();

            if($check_exist){
                return $this->respondFail('This email already exist!', 422);
            }
        }
        if($user->profile_picture AND $request->profile_picture){// Remove old Image
            if($user->profile_picture != $request->profile_picture){ // If old Image 
                $old_file = SystemFile::find($user->profile_picture);
                Storage::delete($old_file->path);
                SystemFile::find($user->profile_picture)->delete();
            }
        }

        $user->name_surname = $request->name_surname;
        $user->email = $request->email;
        $user->profile_picture = $request->profile_picture;
        $update = $user->save();

        if($update){
            return $this->respondSuccess($update, 'Your settings are saved successfully.');
        }else{
            return $this->respondFail('An error occured!', 500);
        }
    }

    public function save_password(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required',
        ]);
        
        $user = Auth::user();

        if(!$user){
            return $this->respondFail('An error occured!', 500);
        }
 
        if (Auth::attempt(['email' => $user->email, 'password' => $request->current_password])) {
            $user->password = Hash::make($request->new_password);

            $update = $user->save();
        }else{
            return $this->respondFail('Current password is not correct!', 422);
        }

        if($update){
            return $this->respondSuccess($update, 'Your password is saved successfully.');
        }else{
            return $this->respondFail('An error occured!', 500);
        }
    }

    public function save_theme(Request $request)
    {
        $request->validate([
            'theme_color' => 'required',
            'theme_layout' => 'required',
            'theme_nav_style' => 'required'
        ]);

        $user = Auth::user();

        $user->theme_color = $request->theme_color;
        $user->theme_layout = $request->theme_layout;
        $user->theme_nav_style = $request->theme_nav_style;

        $update = $user->save();

        if($update){
            return $this->respondSuccess($update);
        }else{
            return $this->respondFail('An error occured!', 500);
        }
    }

}
