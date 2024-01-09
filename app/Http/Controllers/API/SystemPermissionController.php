<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Traits\RespondTrait;
use App\Models\SystemPermission;

class SystemPermissionController extends Controller
{
    use RespondTrait;

    public function list_all()
    {
        $system_permissions = SystemPermission::all()->map(function($system_permission){
            return [
                'id' => $system_permission->id,
                'text' => $system_permission->title
            ];
        });

        if(!$system_permissions){
            return $this->respondFail('An error occured!', 500);
        }

        return $this->respondSuccess($system_permissions);
    }
}
