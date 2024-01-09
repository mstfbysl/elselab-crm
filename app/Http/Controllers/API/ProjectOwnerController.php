<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Traits\RespondTrait;
use App\Models\Project;
use Illuminate\Http\Request;

use App\Models\ProjectOwner;
use App\Models\User;

class ProjectOwnerController extends Controller
{
    use RespondTrait;

    public function list_datatable_filter(Request $request)
    {
        $request->validate([
            'project_id' => 'required',
        ]);

        $users = ProjectOwner::where('project_id', $request->project_id)->orderBy('id', 'desc')->get()->map(function($user){
            $user_data =  User::find($user->user_id);
            $name =  $user_data->name_surname;
            $profile_picture = $user_data->getProfilePicturePreviewAttribute();

            return [
                $user->id,
                '<div class="avatar avatar-sm me-2"><img src="'.$profile_picture.'" alt="Avatar" height="26" width="26"></div>'.$name,
                $user->percentage.' %',
                '<a href="#" onclick="onEditUser('.$user->id.')" class="btn btn-sm btn-primary"><i class="bi bi-pencil"></i></a> <a href="#" onclick="onDeleteUser('.$user->id.')" class="btn btn-sm btn-danger"><i class="bi bi-trash"></i></a>'
            ];
        });

        if(request()->user()->hasPermission(50)){$hidden_collumns = [];}else{$hidden_collumns = [2];}
        return $this->respondSuccessDatatable($users, 1, $users->count(), $hidden_collumns);
    }

    public function detail(Request $request)
    {
        $user = ProjectOwner::find($request->id);

        if(!$user){
            return $this->respondFail('An error occured!', 500);
        }

        return $this->respondSuccess($user);
    }

    public function create(Request $request)
    {
        $request->validate([
            'project_id' => 'required',
            'user_id' => 'required',
        ]);

        $project_user = ProjectOwner::where('project_id', $request->project_id)
                                ->where('user_id', $request->user_id)
                                ->first();

        if($project_user){
            return $this->respondFail('This user already exist in the project!', 422);
        }

        $project = Project::find($request->project_id);

        if(!$project){
            return $this->respondFail('An error occured!', 500);
        }

        $profit_share = User::find($request->user_id)->profit_share;

        $create = ProjectOwner::create([
            'project_id' => $request->project_id,
            'user_id' => $request->user_id,
            'profit_share' => $profit_share,
            'percentage' => $project->remaining_ownerness
        ]);

        if($create)
        {
            $project->used_ownerness += $project->remaining_ownerness;
            $project->save();
    
            return $this->respondSuccess($create->id, 'Project User added successfully.');
        }
        else
        {
            return $this->respondFail('An error occured!', 500);
        }
    }

    public function edit(Request $request)
    {
        $request->validate([
            'percentage' => 'required|min:1|max:100',
        ]);

        $project_user = ProjectOwner::find($request->id);

        if(!$project_user){
            return $this->respondFail('An error occured!', 500);
        }

        $project = Project::find($project_user->project_id);

        if(!$project){
            return $this->respondFail('An error occured!', 500);
        }

        $new_total = $request->percentage + ($project->used_ownerness - $project_user->percentage);

        if($new_total > $project->total_ownerness){
            return $this->respondFail('The total ownerness is exceeded, please reduce percentage.');
        }

        $project_user->profit_share = User::find($project_user->user_id)->profit_share;
        $project_user->percentage = $request->percentage;
        $update = $project_user->save();
        
        if($update)
        {
            $project->used_ownerness = $new_total; 
            $project->save();

            return $this->respondSuccess($update, 'Project User saved successfully.');
        }
        else
        {
            return $this->respondFail('An error occured!', 500);
        }
    }

    public function delete(Request $request)
    {
        $project_user = ProjectOwner::find($request->id);

        if(!$project_user){
            return $this->respondFail('An error occured!', 500);
        }

        $delete = $project_user->delete();

        $project_user->project->used_ownerness -= $project_user->percentage; 
        $project_user->project->save();

        if($delete)
        {
            return $this->respondSuccess($delete, 'Project User deleted successfully.');
        }
        else
        {
            return $this->respondFail('An error occured!', 500);
        }
    }

}
