<?php

namespace App\Http\Controllers\Api;

use App\Helpers\Helpers;
use App\Http\Controllers\Controller;
use App\Http\Traits\RespondTrait;

use App\Models\Project;
use App\Models\Service;
use App\Models\ProjectService;

use Illuminate\Http\Request;

class ProjectServiceController extends Controller
{
    use RespondTrait;

    public function list_datatable_filter(Request $request)
    {
        $request->validate([
            'project_id' => 'required',
        ]);

        $services = ProjectService::where('project_id', $request->project_id)->get()->map(function($service){
            return [
                $service->id,
                $service->service->title,
                $service->quantity,
                Helpers::human_format($service->price),
                Helpers::human_format($service->quantity * $service->price),
                '<a href="#" onclick="onEditService('.$service->id.')" class="btn btn-sm btn-primary"><i class="bi bi-pencil"></i></a> <a href="#" onclick="onDeleteService('.$service->id.')" class="btn btn-sm btn-danger"><i class="bi bi-trash"></i></a>'
            ];
        });

        if(request()->user()->hasPermission(50)){$hidden_collumns = [];}else{$hidden_collumns = [3,4];}
        return $this->respondSuccessDatatable($services, 1, $services->count(), $hidden_collumns);
    }    

    public function detail(Request $request)
    {
        $project_service = ProjectService::find($request->id);

        if(!$project_service){
            return $this->respondFail('An error occured!', 500);
        }

        return $this->respondSuccess($project_service);
    }

    public function create(Request $request)
    {
        $request->validate([
            'project_id' => 'required',
            'service_id' => 'required',
            'quantity' => 'required',
            'price' => 'required',
        ]);

        $project = Project::find($request->project_id);

        if(!$project){
            return $this->respondFail('An error occured!', 500);
        }

        $service = Service::find($request->service_id);

        $create = ProjectService::create([
            'project_id' => $request->project_id,
            'service_id' => $request->service_id,
            'quantity' => $request->quantity,
            'price' => $request->price
        ]);

        if($create){
            return $this->respondSuccess($create->id, 'Project Expense added successfully.');
        }else{
            return $this->respondFail('An error occured!', 500);
        }
    }

    public function edit(Request $request)
    {
        $request->validate([
            'service_id' => 'required',
            'quantity' => 'required',
            'price' => 'required',
        ]);

        $project_service = ProjectService::find($request->id);

        if(!$project_service){
            return $this->respondFail('An error occured!', 500);
        }
       
        $service = Service::find($request->service_id);

        if(!$service){
            return $this->respondFail('An error occured!', 500);
        }
        
        $project = Project::find($project_service->project_id);

        if(!$project){
            return $this->respondFail('An error occured!', 500);
        }

        $project_service->service_id = $request->service_id;
        $project_service->quantity = $request->quantity;
        $project_service->price = $request->price;
        $update = $project_service->save();
        
        if($update){
            return $this->respondSuccess($update, 'Project User saved successfully.');
        }else{
            return $this->respondFail('An error occured!', 500);
        }
    }

    public function delete(Request $request)
    {
        $service = ProjectService::find($request->id);

        if(!$service){
            return $this->respondFail('An error occured!', 500);
        }

        $project = Project::find($service->project_id);

        if(!$project){
            return $this->respondFail('An error occured!', 500);
        }
        
        $delete = $service->delete();

        if($delete)
        {
            return $this->respondSuccess($delete, 'Project Expense deleted successfully.');
        }
        else
        {
            return $this->respondFail('An error occured!', 500);
        }
    }

}
