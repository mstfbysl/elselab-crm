<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Traits\RespondTrait;
use App\Models\Permission;
use App\Models\Role;

class RoleController extends Controller
{
    use RespondTrait;
    
    public function list_all()
    {
        $roles = Role::get()->map(function($role){
            return [
                'id' => $role->id,
                'text' => $role->title
            ];
        });

        if(!$roles){
            return $this->respondFail('An error occured!', 500);
        }

        return $this->respondSuccess($roles);
    }
    
    public function list_datatable()
    {
        $roles = Role::get()->map(function($role){
            return [
                $role->id,
                $role->title,
                '<a href="#" onclick="onEdit('.$role->id.')" class="btn btn-sm btn-primary"><i class="bi bi-pencil"></i></a> <a href="#" onclick="onDelete('.$role->id.')" class="btn btn-sm btn-danger"><i class="bi bi-trash"></i></a>'
            ];
        });

        if(!$roles){
            return $this->respondSuccessDatatable([], 0, 0, 0);
        }

        return $this->respondSuccessDatatable($roles, 1, $roles->count());
    }

    public function detail(Request $request)
    {
        $role = Role::with('permissions.permission')->find($request->id);

        if(!$role){
            return $this->respondFail('An error occured!', 500);
        }

        if($role->permissions){
            $permissions = $role->permissions->map(function($r){
                return $r->permission_id    ;
            });
        }
        
        return $this->respondSuccess([
            'role' => ['id' => $role->id, 'title' => $role->title],
            'permissions' => $permissions
        ]);
    }

    public function create(Request $request)
    {
        $request->validate([
            'title' => 'required|max:255',
        ]);
        
        $create = Role::create([
            'title' => $request->title
        ]);

        if($create){
            return $this->respondSuccess($create->id, 'Role created successfully.');
        }else{
            return $this->respondFail('An error occured!', 500);
        }
    }

    public function edit(Request $request)
    {
        $request->validate([
            'title' => 'required|max:255'
        ]);

        $role = Role::find($request->id);

        if(!$role){
            return $this->respondFail('An error occured!', 500);
        }

        $role->title = $request->title;
        $update = $role->save();

        Permission::where('user_role_id', $role->id)->delete();

        if($request->permissions && gettype($request->permissions) == 'array'){

            foreach ($request->permissions as $p_id) {
                Permission::create([
                    'user_role_id' => $role->id,
                    'permission_id' => $p_id
                ]);
            }
        }

        if($update){
            return $this->respondSuccess($update, 'Role saved successfully.');
        }else{
            return $this->respondFail('An error occured!', 500);
        }
    }

    public function delete(Request $request)
    {
        $role = Role::find($request->id);

        if(!$role){
            return $this->respondFail('An error occured!', 500);
        }

        if($role->id == 1){
            return $this->respondFail('You can not remove administration role!', 422);
        }

        $delete = $role->delete();

        if($delete){
            return $this->respondSuccess($delete, 'Role deleted successfully.');
        }else{
            return $this->respondFail('An error occured!', 500);
        }
    }
}
