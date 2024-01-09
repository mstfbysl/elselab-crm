<?php

namespace App\Http\Controllers\Api;

use App\Helpers\Helpers;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Traits\RespondTrait;
use App\Models\User;
use App\Models\ProjectOwner;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    use RespondTrait;

    public function list_all()
    {
        $users = User::get()->map(function($user){
            return [
                'id' => $user->id,
                'text' => $user->name_surname
            ];
        });

        if(!$users){
            return $this->respondFail('An error occured!', 500);
        }

        return $this->respondSuccess($users);
    }

    public function list_datatable()
    {
        $users = User::with('role')->get()->map(function($user){
            return [
                $user->id,
                $user->role->title,
                $user->name_surname,
                $user->email,
                Helpers::human_format($user->project_profit - $user->user_expenses),
                '<a href="#" onclick="onEdit('.$user->id.')" class="btn btn-sm btn-primary"><i class="bi bi-pencil"></i></a> <a href="#" onclick="onDelete('.$user->id.')" class="btn btn-sm btn-danger"><i class="bi bi-trash"></i></a>'
            ];
        });

        if(!$users){
            return $this->respondSuccessDatatable([], 0, 0, 0);
        }

        return $this->respondSuccessDatatable($users, 1, $users->count());
    }

    public function detail(Request $request)
    {
        $user = User::find($request->id);

        if(!$user){
            return $this->respondFail('An error occured!', 500);
        }

        return $this->respondSuccess($user);
    }

    public function create(Request $request)
    {
        $request->validate([
            'user_role_id' => 'required',
            'name_surname' => 'required',
            'email' => 'required|unique:users|max:255',
            'password' => 'required|max:255',
            'profit_share' => 'required|integer|max:100'
        ]);
        
        $create = User::create([
            'user_role_id' => $request->user_role_id,
            'name_surname' => $request->name_surname,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'profit_share' => $request->profit_share,
        ]);

        if($create){
            return $this->respondSuccess($create->id, 'User created successfully.');
        }else{
            return $this->respondFail('An error occured!', 500);
        }
    }

    public function edit(Request $request)
    {
        $request->validate([
            'user_role_id' => 'required',
            'name_surname' => 'required',
            'email' => 'required|email|max:255',
            'profit_share' => 'required|integer|max:100'
        ]);

        $user = User::find($request->id);

        if(!$user){
            return $this->respondFail('An error occured!', 500);
        }

        if($request->password != ''){
            $user->password = Hash::make($request->password);
        }

        $user->user_role_id = $request->user_role_id;
        $user->name_surname = $request->name_surname;
        $user->email = $request->email;
        $user->profit_share = $request->profit_share;
        $update = $user->save();

        if($update){
            return $this->respondSuccess($update, 'User saved successfully.');
        }else{
            return $this->respondFail('An error occured!', 500);
        }
    }

    public function delete(Request $request)
    {
        $user = User::find($request->id);

        if(!$user){
            return $this->respondFail('An error occured!', 500);
        }

        if($user->is_main_admin == 1){
            return $this->respondFail('You can not remove main user!', 422);
        }

        $delete = $user->delete();

        if($delete){
            return $this->respondSuccess($delete, 'User deleted successfully.');
        }else{
            return $this->respondFail('An error occured!', 500);
        }
    }
}
