<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Traits\RespondTrait;
use Illuminate\Http\Request;

use App\Models\ProjectStatus;
use Illuminate\Support\Facades\Artisan;

class ProjectStatusController extends Controller
{
    use RespondTrait;
    
    public function list_all()
    {
        $statuses = ProjectStatus::all()->map(function($status){
            return [
                'id' => $status->id,
                'text' => $status->title,
                'color' => $status->color
            ];
        });

        if(!$statuses){
            return $this->respondFail('An error occured!', 500);
        }

        return $this->respondSuccess($statuses);
    }

    public function list_datatable()
    {
        $statuses = ProjectStatus::get()->map(function($status){

            return [
                $status->id,
                $status->title,
                '<span class="badge rounded-pill" style="color:#fff; background-color:'.$status->color.'">'.$status->title.'</span>',
                '<a href="#" onclick="onEdit('.$status->id.')" class="btn btn-sm btn-primary"><i class="bi bi-pencil"></i></a> <a href="#" onclick="onDelete('.$status->id.')" class="btn btn-sm btn-danger"><i class="bi bi-trash"></i></a>'
            ];
        });

        if(!$statuses){
            return $this->respondSuccessDatatable([], 0, 0, 0);
        }

        return $this->respondSuccessDatatable($statuses, 1, $statuses->count());
    }

    public function detail(Request $request)
    {
        $status = ProjectStatus::find($request->id);

        if(!$status){
            return $this->respondFail('An error occured!', 500);
        }

        return $this->respondSuccess($status);
    }

    public function create(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'color' => 'required',
        ]);
        
        $create = ProjectStatus::create([
            'title' => $request->title,
            'color' => $request->color,
        ]);

        if($create){
            Artisan::call('cache:clear');
            return $this->respondSuccess($create->id, 'Status created successfully.');
        }else{
            return $this->respondFail('An error occured!', 500);
        }
    }

    public function edit(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'color' => 'required',
        ]);

        $status = ProjectStatus::find($request->id);

        if(!$status){
            return $this->respondFail('An error occured!', 500);
        }

        $status->title = $request->title;
        $status->color = $request->color;
        $update = $status->save();

        if($update){
            Artisan::call('cache:clear');
            return $this->respondSuccess($update, 'Status saved successfully.');
        }else{
            return $this->respondFail('An error occured!', 500);
        }
    }

    public function delete(Request $request)
    {
        $status = ProjectStatus::find($request->id);

        if(!$status){
            return $this->respondFail('An error occured!', 500);
        }

        $delete = $status->delete();

        if($delete){
            Artisan::call('cache:clear');
            return $this->respondSuccess($delete, 'Status deleted successfully.');
        }else{
            return $this->respondFail('An error occured!', 500);
        }
    }
}
