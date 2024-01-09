<?php

namespace App\Http\Controllers\Api;

use App\Helpers\Helpers;
use App\Http\Controllers\Controller;
use App\Http\Traits\RespondTrait;
use Illuminate\Http\Request;

use App\Models\Service;

class ServiceController extends Controller
{
    use RespondTrait;
    
    public function list_all()
    {
        $services = Service::all()->map(function($service){
            return [
                'id' => $service->id,
                'text' => $service->title.' ('.$service->price.')'
            ];
        });

        if(!$services){
            return $this->respondFail('An error occured!', 500);
        }

        return $this->respondSuccess($services);
    }

    public function list_datatable()
    {
        $services = Service::get()->map(function($service){

            return [
                $service->id,
                $service->title,
                Helpers::human_format($service->price),
                '<a href="#" onclick="onEdit('.$service->id.')" class="btn btn-sm btn-primary"><i class="bi bi-pencil"></i></a> <a href="#" onclick="onDelete('.$service->id.')" class="btn btn-sm btn-danger"><i class="bi bi-trash"></i></a>'
            ];
        });

        if(!$services){
            return $this->respondSuccessDatatable([], 0, 0, 0);
        }

        return $this->respondSuccessDatatable($services, 1, $services->count());
    }

    public function detail(Request $request)
    {
        $service = Service::find($request->id);

        if(!$service){
            return $this->respondFail('An error occured!', 500);
        }

        return $this->respondSuccess($service);
    }

    public function create(Request $request)
    {
        $request->validate([
            'title' => 'required',
        ]);
        
        $create = Service::create([
            'title' => $request->title,
            'price' => $request->price,
        ]);

        if($create){
            return $this->respondSuccess($create->id, 'service created successfully.');
        }else{
            return $this->respondFail('An error occured!', 500);
        }
    }

    public function edit(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'price' => 'required',
        ]);

        $service = Service::find($request->id);

        if(!$service){
            return $this->respondFail('An error occured!', 500);
        }

        $service->title = $request->title;
        $service->price = $request->price;
        $update = $service->save();

        if($update){
            return $this->respondSuccess($update, 'service saved successfully.');
        }else{
            return $this->respondFail('An error occured!', 500);
        }
    }

    public function delete(Request $request)
    {
        $service = Service::find($request->id);

        if(!$service){
            return $this->respondFail('An error occured!', 500);
        }

        $delete = $service->delete();

        if($delete){
            return $this->respondSuccess($delete, 'service deleted successfully.');
        }else{
            return $this->respondFail('An error occured!', 500);
        }
    }
}
